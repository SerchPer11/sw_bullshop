<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bussiness\TrackOrderRequest;
use App\Http\Resources\Bussiness\OrderResource;
use App\Http\Services\CheckoutService;
use App\Http\Services\TrackOrderService;
use App\Mail\OrderDeliveredMail;
use App\Models\Bussiness\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class OrderTrackingController extends Controller
{
    protected string $source;

    protected string $route;

    protected TrackOrderService $trackOrderService;

    public function __construct(TrackOrderService $trackOrderService)
    {
        $this->trackOrderService = $trackOrderService;
        $this->source = 'Bussiness/Order/Pages/';
        $this->route = 'order.';
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            return $this->trackOrderService->getOrdersAuth(
                Auth::user()->id,
                $this->route,
                $this->source,
                [
                    'search' => $request->string('search')->trim()->value(),
                    'per_page' => 3,
                    'page' => $request->integer('page', 1),
                ],
            );
        }

        return Inertia::render("{$this->source}Index", [
            'title' => 'Rastreo de pedido',
            'route' => $this->route,
            'orders' => null,
            'order' => null,
            'isAuthenticated' => false,
            'filters' => ['search' => '', 'per_page' => 8, 'page' => 1],
        ]);
    }

    public function search(TrackOrderRequest $request)
    {
        $validated = $request->validated();
        $order = Auth::check()
            ? $this->trackOrderService->getOrderAuth(Auth::id(), $validated['order_number'])
            : $this->trackOrderService->getOrdersGuest($validated['order_number'], $validated['email']);

        if (! $order) {
            return back()->with('error', 'No se encontró ningún pedido.');
        }

        return Inertia::render("{$this->source}Index", [
            'title' => 'Rastreo de pedido',
            'route' => $this->route,
            'orders' => Auth::check() ? OrderResource::collection([$order]) : null,
            'order' => Auth::check() ? null : OrderResource::make($order),
            'isAuthenticated' => Auth::check(),
        ]);
    }

    public function showCustomer(string $orderNumber)
    {
        $order = $this->trackOrderService->getOrderByNumber($orderNumber);

        abort_if(! $order, 404);

        return Inertia::render("{$this->source}Index", [
            'title' => 'Detalle de tu pedido',
            'route' => $this->route,
            'orders' => null,
            'order' => OrderResource::make($order),
            'isAuthenticated' => Auth::check(),
        ]);
    }

    public function indexStaff(Request $request)
    {
        abort_unless($this->canManageOrders(), 403);

        $filters = [
            'search' => $request->string('search')->trim()->value(),
            'per_page' => 8,
            'page' => $request->integer('page', 1),
        ];
        $orders = $this->trackOrderService->getAllOrders($filters);

        return Inertia::render("{$this->source}StaffIndex", [
            'title' => 'Rastreo de pedidos',
            'route' => $this->route,
            'orders' => OrderResource::collection($orders),
            'filters' => $filters,
        ]);
    }

    public function show(string $orderNumber)
    {
        abort_unless($this->canManageOrders(), 403);

        $order = $this->trackOrderService->getOrderByNumber($orderNumber);

        abort_if(! $order, 404);

        return Inertia::render("{$this->source}StaffShow", [
            'title' => 'Detalle del pedido',
            'route' => $this->route,
            'order' => OrderResource::make($order),
        ]);
    }

    public function markPayment(string $orderNumber)
    {
        abort_unless($this->canManageOrders(), 403);

        $order = DB::transaction(function () use ($orderNumber): Order {
            $order = Order::query()
                ->lockForUpdate()
                ->where('order_number', $orderNumber)
                ->firstOrFail();

            abort_if($order->payment_method !== 1 || $order->payment_status === 'paid', 422, 'El pago no puede actualizarse.');

            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'reserved' : $order->order_status,
            ]);

            return $order;
        });

        return back()->with('success', "Pago confirmado para {$order->order_number}.");
    }

    public function markDelivery(string $orderNumber)
    {
        abort_unless($this->canManageOrders(), 403);

        $order = DB::transaction(function () use ($orderNumber): Order {
            $order = Order::query()
                ->lockForUpdate()
                ->where('order_number', $orderNumber)
                ->firstOrFail();

            abort_if($order->payment_status !== 'paid', 422, 'No puedes confirmar la entrega antes del pago.');
            abort_if($order->order_status === 'completed', 422, 'La entrega ya fue confirmada.');

            $order->update(['order_status' => 'completed']);

            return $order;
        });

        Mail::to($order->customer_email)->send(new OrderDeliveredMail($order));

        return back()->with('success', "Entrega confirmada para {$order->order_number}.");
    }

    public function refund(Order $order, CheckoutService $checkoutService): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403, 'Solo un administrador puede ejecutar reembolsos.');

        $checkoutService->cancelOrder($order);

        return back()->with('success', 'Pedido cancelado correctamente');
    }

    public function cancelCash(Order $order, CheckoutService $checkoutService): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403, 'Solo un administrador puede cancelar pedidos.');

        $checkoutService->cancelOrder($order);

        return back()->with('success', 'Pedido cancelado correctamente');
    }

    private function canManageOrders(): bool
    {
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', Auth::id())
            ->whereIn('roles.name', ['admin', 'staff'])
            ->exists();
    }

    private function isAdmin(): bool
    {
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', Auth::id())
            ->where('roles.name', 'admin')
            ->exists();
    }
}
