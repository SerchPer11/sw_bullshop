<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
use App\Mail\POSReceiptMail;
use App\Models\Bussiness\Order;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use App\Models\Bussiness\ProductVariant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class POSController extends Controller
{
    protected string $source;

    public function __construct()
    {
        $this->source = 'Bussiness/POS/Pages/';
    }

    public function index(): Response
    {
        abort_unless($this->canAccessPOS(), 403);

        $products = Product::where('is_active', true)
            ->with([
                'variants' => fn ($query) => $query->where('is_active', true)->orderBy('price'),
                'photos',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $packages = Package::where('is_active', true)
            ->with([
                'photos',
                'products' => fn ($query) => $query->where('is_active', true),
                'products.variants' => fn ($query) => $query->where('is_active', true)->orderBy('price'),
                'products.photos',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render("{$this->source}Index", [
            'title' => 'Punto de Venta (POS)',
            'productsData' => ProductResource::collection($products),
            'packagesData' => PackageResource::collection($packages),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($this->canAccessPOS(), 403);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'string', Rule::in(['variant', 'bundle'])],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.variant_id' => ['nullable', 'required_if:items.*.type,variant', 'string', 'exists:product_variants,id'],
            'items.*.package_id' => ['nullable', 'required_if:items.*.type,bundle', 'string', 'exists:packages,id'],
            'items.*.selections' => ['nullable', 'required_if:items.*.type,bundle', 'array'],
            'items.*.selections.*' => ['required', 'string', 'exists:product_variants,id'],
        ], [
            'email.required' => 'El correo electrónico del cliente es obligatorio para enviar su recibo.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'items.required' => 'Debes agregar al menos un artículo al ticket.',
            'items.min' => 'Debes agregar al menos un artículo al ticket.',
        ]);

        $order = DB::transaction(function () use ($validated): Order {
            $subtotal = 0;
            $saleTotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $cartItem) {
                $quantity = (int) $cartItem['quantity'];
                $type = $cartItem['type'];

                if ($type === 'variant') {
                    $variant = ProductVariant::with('product')
                        ->lockForUpdate()
                        ->find($cartItem['variant_id']);

                    if (! $variant || $variant->stock < $quantity) {
                        $variantLabel = $variant
                            ? "{$variant->product?->name} ({$variant->name})"
                            : 'seleccionado';

                        throw ValidationException::withMessages([
                            'stock_error' => "Stock insuficiente para {$variantLabel}. Disponible: ".($variant?->stock ?? 0),
                        ]);
                    }

                    // Descontamos el stock de la variante individual
                    $variant->decrement('stock', $quantity);

                    $lineSaleTotal = (float) $variant->price * $quantity;
                    $lineSubtotal = (float) ($variant->compare_at_price ?? $variant->price) * $quantity;

                    $subtotal += $lineSubtotal;
                    $saleTotal += $lineSaleTotal;

                    $orderItemsData[] = [
                        'product_id' => $variant->product_id,
                        'package_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $variant->price,
                        'attributes_selected' => json_encode([
                            'variant_id' => $variant->id,
                            'variant_name' => $variant->name,
                        ]),
                    ];
                } elseif ($type === 'bundle') {
                    $package = Package::lockForUpdate()->find($cartItem['package_id']);

                    if (! $package || ! $package->is_active) {
                        throw ValidationException::withMessages([
                            'stock_error' => 'El paquete seleccionado ya no está disponible.',
                        ]);
                    }

                    $selections = $cartItem['selections'] ?? [];

                    // Validamos y descontamos cada variante que compone el paquete (igual que en CheckoutService)
                    foreach ($selections as $productId => $variantId) {
                        $variant = ProductVariant::with('product')
                            ->lockForUpdate()
                            ->find($variantId);

                        if (! $variant || $variant->stock < $quantity) {
                            $variantName = $variant ? $variant->name : 'seleccionada';

                            throw ValidationException::withMessages([
                                'stock_error' => "Lo sentimos, el artículo {$variantName} del paquete {$package->name} se ha agotado o no tiene suficiente stock.",
                            ]);
                        }

                        $variant->decrement('stock', $quantity);
                    }

                    $lineSaleTotal = (float) $package->price * $quantity;
                    $lineSubtotal = (float) ($package->compare_at_price ?? $package->price) * $quantity;

                    $subtotal += $lineSubtotal;
                    $saleTotal += $lineSaleTotal;

                    $orderItemsData[] = [
                        'product_id' => null,
                        'package_id' => $package->id,
                        'quantity' => $quantity,
                        'unit_price' => $package->price,
                        'attributes_selected' => json_encode([
                            'selections' => $selections,
                        ]),
                    ];
                }
            }

            $discount = round(max(0, $subtotal - $saleTotal), 2);
            $totalAmount = round($saleTotal, 2);

            $year = now()->year;
            do {
                $randomStr = strtoupper(Str::random(6));
                $orderNumber = "BS-{$year}-{$randomStr}";
            } while (Order::where('order_number', $orderNumber)->exists());

            $existingUser = User::where('email', $validated['email'])->first();

            // CORRECCIÓN CRÍTICA:
            // En BullShop, el estatus final de entrega completada es 'completed' (ver OrderTrackingController@markDelivery y StaffShow.vue).
            // Al crearla con order_status = 'completed' y payment_status = 'paid', el ciclo logístico nace 100% cerrado.
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $existingUser?->id,
                'customer_name' => ! empty($validated['customer_name']) ? $validated['customer_name'] : 'Venta Pop-Up',
                'customer_email' => $validated['email'],
                'customer_timezone' => config('app.timezone'),
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 3, // 1: efectivo apartado, 2: stripe, 3: POS Pop-Up
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => 0,
                'total_amount' => $totalAmount,
                'confirmation_sent_at' => now(),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            return $order;
        });

        Mail::to($order->customer_email)->send(new POSReceiptMail($order));

        return back()->with('success', "Venta registrada con éxito ({$order->order_number}). Recibo enviado a {$order->customer_email}.");
    }

    private function canAccessPOS(): bool
    {
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', Auth::id())
            ->whereIn('roles.name', ['admin', 'staff'])
            ->exists();
    }
}
