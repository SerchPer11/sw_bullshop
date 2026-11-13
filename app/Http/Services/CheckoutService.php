<?php

namespace App\Http\Services;

use App\Jobs\ReleasePendingOrder;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderConfirmation;
use App\Models\Bussiness\Coupon;
use App\Models\Bussiness\Lead;
use App\Models\Bussiness\Order;
use App\Models\Bussiness\OrderItem;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use App\Models\Bussiness\ProductVariant;
use App\Models\User;
use App\Models\Users\CustomerAddress;
use App\Models\Users\CustomerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Coupon as StripeCoupon;
use Stripe\Refund as StripeRefund;
use Stripe\Stripe;

class CheckoutService
{
    public function processCheckout(array $validatedData)
    {
        $carItems = $validatedData['cart_items'];
        // dd($carItems, $validatedData);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $saleTotal = 0;
            $orderItemsData = [];
            $discount = 0;
            $couponId = null;

            // Validación de stock y cálculo del total
            foreach ($carItems as $item) {
                $quantity = $item['quantity'];
                $type = $item['type'];
                $payload = $item['backend_payload'];

                if ($type === 'variant') {
                    $variantId = $payload['id'];
                    $variant = ProductVariant::lockForUpdate()->find($variantId);

                    // Validamos si el producto existe y si hay suficiente stock
                    if (! $variant || $variant->stock < $quantity) {
                        throw ValidationException::withMessages([
                            'stock_error' => "Lo sentimos, el artículo {$variant->name} se ha agotado o no tiene suficiente stock.",
                            'affected_item_key' => $item['uniqueKey'],
                            'available_quantity' => $variant ? $variant->stock : 0,
                        ]);
                    }

                    // Restamos el stock del producto
                    $variant->decrement('stock', $quantity);
                    $saleSubtotal = $variant->price * $quantity;
                    $subtotal += ($variant->compare_at_price ?? $variant->price) * $quantity;
                    $saleTotal += $saleSubtotal;

                    // Preparación de datos para crear la orden
                    $orderItemsData[] = [
                        'product_id' => $variant->product_id,
                        'package_id' => null,
                        'quantity' => $quantity,
                        'cart_item_key' => $item['uniqueKey'] ?? null,
                        'unit_price' => $variant->price,
                        'attributes_selected' => json_encode([
                            'variant_id' => $variant->id,
                            'variant_name' => $variant->name,
                        ]),
                    ];
                } elseif ($type === 'bundle') {
                    $packageId = $payload['id'];
                    $package = Package::find($packageId);
                    $selections = $payload['selections'];

                    // Validamos cada vairante que forma parte del paquete
                    foreach ($selections as $selection => $variantId) {
                        $variant = ProductVariant::lockForUpdate()->find($variantId);

                        if (! $variant || $variant->stock < $quantity) {
                            throw ValidationException::withMessages([
                                'stock_error' => "Lo sentimos, el artículo {$variant->name} del paquete {$package->name} se ha agotado o no tiene suficiente stock.",
                                'affected_item_key' => $item['uniqueKey'],
                                'available_quantity' => $variant ? $variant->stock : 0,
                            ]);
                        }

                        // Restamos el stock del producto
                        $variant->decrement('stock', $quantity);
                    }

                    $saleSubtotal = $package->price * $quantity;
                    $subtotal += ($package->compare_at_price ?? $package->price) * $quantity;
                    $saleTotal += $saleSubtotal;

                    // Preparación de datos para crear la orden
                    $orderItemsData[] = [
                        'product_id' => null,
                        'package_id' => $package->id,
                        'quantity' => $quantity,
                        'cart_item_key' => $item['uniqueKey'] ?? null,
                        'unit_price' => $package->price,
                        'attributes_selected' => json_encode([
                            'selections' => $selections,
                        ]),
                    ];
                }
            }

            // Validamos el cupón
            $couponData = $this->validateCoupon($validatedData['coupon_code_id'] ?? null, $saleTotal);
            $couponDiscount = is_array($couponData) ? $couponData['discount'] : 0;
            $couponId = is_array($couponData) ? $couponData['coupon_id'] : null;
            $couponType = is_array($couponData) ? $couponData['type'] : null;
            $productDiscount = round(max(0, $subtotal - $saleTotal), 2);
            $discount = round($productDiscount + $couponDiscount, 2);

            $shippingCost = 0; // por ahora no hay envio, pero si se agrega, se puede calcular aquí
            $totalAmount = round(max(0, $saleTotal - $couponDiscount + $shippingCost), 2);

            // Manejo de usuario: si el usuario no está logueado y quiere crear cuenta, se crea el usuario
            $user = $this->manageUser($validatedData);

            // Creamos la orden
            $order = $this->createOrder($user, $couponId, $subtotal, $discount, $couponType, $shippingCost, $totalAmount, $validatedData, $orderItemsData);

            DB::commit();

            if ($validatedData['payment_method'] === 1) {
                $this->queueOrderConfirmation($order);

                // efectivo, redirigir a la página de confirmación
                return redirect()->route('checkout.success', ['order_id' => $order->id]);
            } elseif ($validatedData['payment_method'] === 2) {
                // stripe, redirigir a la pasarela de Stripe
                $stripeUrl = $this->checkoutStripe($order, $carItems, $shippingCost, $couponDiscount, $couponType);

                return Inertia::location($stripeUrl);
            }
        } catch (ValidationException $e) {
            // Si falta stock, Laravel deshace los cambios automáticamente y Vue atrapa este error
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            // Errores graves
            throw ValidationException::withMessages(['error' => 'Ocurrió un error al procesar tu pedido. Intenta de nuevo.', 'details' => $e->getMessage()]);
        }
    }

    public function queueOrderConfirmation(Order $order): void
    {
        $wasMarked = Order::query()
            ->whereKey($order->id)
            ->whereNull('confirmation_sent_at')
            ->update(['confirmation_sent_at' => now()]);

        if ($wasMarked === 0) {
            return;
        }

        Mail::to($order->customer_email)
            ->queue((new OrderConfirmation($order))->afterCommit());
    }

    public function validateCoupon(?int $couponCodeId, float $saleTotal): array|int
    {
        if ($couponCodeId) {
            $coupon = Coupon::lockForUpdate()
                ->where('id', $couponCodeId)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->first();

            if (! $coupon) {
                throw ValidationException::withMessages([
                    'coupon_error' => 'El cupón seleccionado ya no es válido o ha expirado.',
                ]);
            }

            if ($coupon->hasReachedUsageLimit()) {
                throw ValidationException::withMessages([
                    'coupon_error' => "El cupón {$coupon->code} ha alcanzado su límite máximo de usos.",
                ]);
            }

            if ($saleTotal >= $coupon->minimum_purchase) {
                $discount = $coupon->discount_type === 'percentage'
                    ? ($saleTotal * $coupon->discount_value) / 100
                    : min($coupon->discount_value, $saleTotal);

                // Incrementamos el contador de usos del cupón dentro de la transacción del Checkout
                $coupon->increment('used_count');

                return [
                    'discount' => round($discount, 2),
                    'coupon_id' => $coupon->id,
                    'type' => $coupon->discount_type,
                ];
            }
        }

        return 0;
    }

    public function manageUser(array $data): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $user = User::where('email', $data['email'])->first();

        if ($user) {
            return $user;
        }

        if ($data['create_account'] ?? false) {

            $lead = Lead::where('email', $data['email'])->first();

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['shipping']['name'],
                    'lastname' => $data['shipping']['lastname'],
                    'password' => Hash::make($data['password']),
                ]
            );

            // Agregar logica de direccion de usuario

            CustomerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone_number' => $data['shipping']['phone'] ?? $lead?->phone,
                ]
            );

            CustomerAddress::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'street' => $data['shipping']['street'] ?? null,
                    'outdoor_number' => $data['shipping']['outdoor_number'] ?? null,
                    'indoor_number' => $data['shipping']['indoor_number'] ?? null,
                    'colony' => $data['shipping']['colony'] ?? null,
                    'city' => $data['shipping']['city'] ?? null,
                    'state' => $data['shipping']['state'] ?? null,
                    'postal_code' => $data['shipping']['zip'] ?? null,
                ]
            );

            return $user;
        }

        return null;
    }

    public function createOrder(?User $user, ?int $couponId, float $subtotal, int|float $discount, ?string $couponType, float $shippingCost, float $totalAmount, array $data, array $orderItemsData): Order
    {
        $year = now()->year;
        $userId = $user?->id;
        $userEmail = $user?->email ?? $data['email'];

        do {
            $randomStr = strtoupper(Str::random(6));
            $orderNumber = "BS-{$year}-{$randomStr}";
        } while (Order::where('order_number', $orderNumber)->exists());

        $this->validateOrderLimit($userId, $userEmail, $orderItemsData);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $userId,
            'customer_name' => $data['shipping']['name'].' '.$data['shipping']['lastname'],
            'customer_email' => $data['email'],
            'customer_timezone' => $data['timezone'] ?? config('app.timezone'),
            'customer_phone' => $data['shipping']['phone'] ?? null,
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'coupon_id' => $couponId,
            'discount_amount' => $discount,
            'street' => $data['shipping']['street'] ?? null,
            'outdoor_number' => $data['shipping']['outdoor_number'] ?? null,
            'indoor_number' => $data['shipping']['indoor_number'] ?? null,
            'city' => $data['shipping']['city'] ?? null,
            'state' => $data['shipping']['state'] ?? null,
            'colony' => $data['shipping']['colony'] ?? null,
            'postal_code' => $data['shipping']['zip'] ?? null,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
        ]);

        foreach ($orderItemsData as $itemData) {
            unset($itemData['cart_item_key']);
            $order->items()->create($itemData);
        }

        if ($order->payment_method === 2) {
            // Si es pago con Stripe, programamos un job para liberar la orden si no se paga en 10 minutos
            ReleasePendingOrder::dispatch($order->id)->delay(now()->addMinutes(10));
        }

        return $order;
    }

    public function validateOrderLimit(?string $userId, string $userEmail, array $orderItems): void
    {
        $requestedProducts = [];
        $requestedPackages = [];
        $productCartKeys = [];
        $packageCartKeys = [];

        foreach ($orderItems as $item) {
            if ($item['product_id'] ?? null) {
                $requestedProducts[$item['product_id']] = ($requestedProducts[$item['product_id']] ?? 0) + $item['quantity'];
                $productCartKeys[$item['product_id']][] = $item['cart_item_key'] ?? null;
            }

            if ($item['package_id'] ?? null) {
                $requestedPackages[$item['package_id']] = ($requestedPackages[$item['package_id']] ?? 0) + $item['quantity'];
                $packageCartKeys[$item['package_id']][] = $item['cart_item_key'] ?? null;
            }
        }

        $customerOrders = function ($query) use ($userId, $userEmail): void {
            $query->where('order_status', '!=', 'cancelled')
                ->where(function ($query) use ($userId, $userEmail): void {
                    if ($userId) {
                        $query->where('user_id', $userId)
                            ->orWhere('customer_email', $userEmail);
                    } else {
                        $query->whereNull('user_id')
                            ->where('customer_email', $userEmail);
                    }
                });
        };

        $purchasedProducts = OrderItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as purchased_quantity')
            ->whereIn('product_id', array_keys($requestedProducts))
            ->whereHas('order', $customerOrders)
            ->groupBy('product_id')
            ->pluck('purchased_quantity', 'product_id');

        $products = Product::query()
            ->whereIn('id', array_keys($requestedProducts))
            ->where('limit_per_user', '>', 0)
            ->get(['id', 'name', 'limit_per_user'])
            ->keyBy('id');

        foreach ($products as $product) {
            $purchasedQuantity = (int) ($purchasedProducts[$product->id] ?? 0);
            $requestedQuantity = $requestedProducts[$product->id];

            if ($purchasedQuantity + $requestedQuantity > $product->limit_per_user) {
                throw ValidationException::withMessages([
                    'order_limit' => "Solo puedes comprar {$product->limit_per_user} unidad(es) del producto {$product->name}. Ya has comprado {$purchasedQuantity}.",
                    'affected_item_key' => end($productCartKeys[$product->id]),
                    'available_quantity' => 0,
                ]);
            }
        }

        $purchasedPackages = OrderItem::query()
            ->select('package_id')
            ->selectRaw('SUM(quantity) as purchased_quantity')
            ->whereIn('package_id', array_keys($requestedPackages))
            ->whereHas('order', $customerOrders)
            ->groupBy('package_id')
            ->pluck('purchased_quantity', 'package_id');

        $packages = Package::query()
            ->whereIn('id', array_keys($requestedPackages))
            ->where('limit_per_user', '>', 0)
            ->get(['id', 'name', 'limit_per_user'])
            ->keyBy('id');

        foreach ($packages as $package) {
            $purchasedQuantity = (int) ($purchasedPackages[$package->id] ?? 0);
            $requestedQuantity = $requestedPackages[$package->id];

            if ($purchasedQuantity + $requestedQuantity > $package->limit_per_user) {
                throw ValidationException::withMessages([
                    'order_limit' => "Solo puedes comprar {$package->limit_per_user} unidad(es) del paquete {$package->name}. Ya has comprado {$purchasedQuantity}.",
                    'affected_item_key' => end($packageCartKeys[$package->id]),
                    'available_quantity' => 0,
                ]);
            }
        }
    }

    public function checkoutStripe(
        Order $order,
        array $carItems,
        float $shippingCost,
        float $couponDiscount = 0,
        ?string $couponType = null
    ) {
        // stripe, redirigir a la página de pago
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];

        foreach ($carItems as $item) {
            // Obtenemos el precio unitario dependiendo si es variante o paquete
            $unitPrice = $item['type'] === 'variant'
                ? ProductVariant::find($item['backend_payload']['id'])->price
                : Package::find($item['backend_payload']['id'])->price;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn', // O la moneda que uses (usd, eur)
                    'product_data' => [
                        'name' => $item['type'] === 'variant' ? 'Variante ID: '.$item['backend_payload']['id'] : 'Paquete',
                        // Si tienes el nombre real a la mano en $item, úsalo en lugar de "Variante ID..."
                    ],
                    'unit_amount' => (int) ($unitPrice * 100), // Stripe exige centavos
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($shippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => ['name' => 'Costo de Envío'],
                    'unit_amount' => (int) ($shippingCost * 100),
                ],
                'quantity' => 1,
            ];
        }

        $stripeDiscounts = [];

        if ($couponDiscount > 0 && $couponType) {
            $stripeCouponData = [
                'name' => $order->coupon?->code ?? 'Cupón de descuento',
                'duration' => 'once',
            ];

            if ($couponType === 'percentage') {
                $stripeCouponData['percent_off'] = $order->coupon->discount_value;
            } else {
                $stripeCouponData['amount_off'] = (int) round($couponDiscount * 100);
                $stripeCouponData['currency'] = 'mxn';
            }

            $stripeCoupon = StripeCoupon::create($stripeCouponData);
            $stripeDiscounts[] = ['coupon' => $stripeCoupon->id];
        }

        $sessionData = [
            'customer_email' => $order->customer_email,
            'line_items' => $lineItems,
            'mode' => 'payment',
            // METADATA: Vital para saber qué orden pagaron o reembolsaron cuando Stripe nos avise
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ],
            // URLs de retorno
            'success_url' => route('checkout.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.stripe.cancel', ['order_id' => $order->id]),
        ];

        if ($stripeDiscounts) {
            $sessionData['discounts'] = $stripeDiscounts;
        }

        $checkoutSession = StripeSession::create($sessionData);

        // 5. Actualizar la orden con el ID de Stripe para rastrearla
        $order->update(['stripe_payment_id' => $checkoutSession->id]);

        return $checkoutSession->url;
    }

    /**
     * Cancela una orden en efectivo inmediatamente o solicita el reembolso asíncrono a Stripe.
     *
     * @throws \Exception
     */
    public function cancelOrder(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            $lockedOrder = Order::with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->order_status === 'shipped') {
                throw ValidationException::withMessages([
                    'error' => 'No se puede cancelar un pedido que ya ha sido enviado.',
                ]);
            }

            if (in_array($lockedOrder->order_status, ['completed', 'delivered', 'cancelled'], true)) {
                throw ValidationException::withMessages([
                    'error' => 'Este pedido ya fue entregado o cancelado previamente.',
                ]);
            }

            $isStripe = (int) $lockedOrder->payment_method === 2;

            // Si fue pagado con Stripe, emitimos el reembolso síncronamente antes de actualizar BD y stock
            if ($isStripe && $lockedOrder->payment_status === 'paid') {
                if (empty($lockedOrder->stripe_payment_id)) {
                    throw ValidationException::withMessages([
                        'error' => 'La orden no cuenta con un ID de sesión de Stripe válido.',
                    ]);
                }

                Stripe::setApiKey(env('STRIPE_SECRET'));

                $paymentIntentId = $lockedOrder->stripe_payment_id;

                // En BD guardamos el ID de la Sesión (cs_test_... / cs_live_...), recuperamos su payment_intent
                if (str_starts_with($paymentIntentId, 'cs_')) {
                    $session = StripeSession::retrieve($paymentIntentId);
                    $paymentIntentId = $session->payment_intent;
                }

                if (empty($paymentIntentId)) {
                    throw ValidationException::withMessages([
                        'error' => 'No se pudo obtener el Payment Intent de Stripe para esta orden.',
                    ]);
                }

                StripeRefund::create([
                    'payment_intent' => $paymentIntentId,
                ]);
            }

            // Restauramos el stock de variantes individuales y paquetes
            $this->restoreOrderStock($lockedOrder);

            // Actualizamos estados según el método de pago
            $lockedOrder->update([
                'order_status' => 'cancelled',
                'payment_status' => $isStripe ? 'refunded' : 'cancelled',
            ]);
        });

        Mail::to($order->customer_email)->send(new OrderCancelledMail($order->fresh()));
    }

    /**
     * Restaura el inventario de variantes (productos individuales y paquetes) y libera el uso del cupón.
     * Debe llamarse dentro de una transacción activa.
     */
    public function restoreOrderStock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            $attrs = is_string($item->attributes_selected)
                ? json_decode($item->attributes_selected, true)
                : ($item->attributes_selected ?? []);

            while (is_string($attrs)) {
                $decoded = json_decode($attrs, true);
                if (json_last_error() !== JSON_ERROR_NONE || $decoded === $attrs) {
                    $attrs = [];
                    break;
                }
                $attrs = $decoded;
            }

            $variantIds = [];

            if ($item->product_id && ($attrs['variant_id'] ?? null)) {
                $variantIds[] = $attrs['variant_id'];
            } elseif ($item->package_id && isset($attrs['selections']) && is_array($attrs['selections'])) {
                $variantIds = array_values($attrs['selections']);
            }

            foreach ($variantIds as $variantId) {
                ProductVariant::whereKey($variantId)
                    ->lockForUpdate()
                    ->increment('stock', $item->quantity);
            }
        }

        if ($order->coupon_id) {
            $coupon = Coupon::lockForUpdate()->find($order->coupon_id);
            $coupon?->releaseUsage();
        }
    }
}
