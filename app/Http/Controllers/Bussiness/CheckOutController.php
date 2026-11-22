<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bussiness\StoreCheckoutRequest;
use App\Http\Resources\Bussiness\OrderResource;
use App\Http\Services\CheckoutService;
use App\Models\Bussiness\Coupon;
use App\Models\Bussiness\Order;
use App\Models\Bussiness\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    protected string $source;

    protected string $route;

    protected CheckoutService $checkoutService;

    public function __construct()
    {
        $this->source = 'Bussiness/Checkout/';
        $this->route = 'checkout.';
        $this->checkoutService = new CheckoutService;
    }

    public function index()
    {
        return Inertia::render("{$this->source}Pages/Index", [
            'title' => 'Checkout',
            'route' => $this->route,
        ]);
    }

    public function store(StoreCheckoutRequest $request)
    {

        return $this->checkoutService->processCheckout($request->validated());
    }

    public function success(string $order_id)
    {
        $order = Order::findOrFail($order_id);

        return Inertia::render("{$this->source}Components/SuccessView", [
            'title' => 'Compra Exitosa',
            'order' => OrderResource::make($order),
            'orderTrackingUrl' => $this->orderTrackingUrl($order),
        ]);
    }

    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->input('session_id');

        $order = Order::where('stripe_payment_id', $sessionId)->firstOrFail();

        return Inertia::render("{$this->source}Components/SuccessView", [
            'title' => 'Pago Exitoso',
            'order' => OrderResource::make($order),
            'orderTrackingUrl' => $this->orderTrackingUrl($order),
        ]);
    }

    private function orderTrackingUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'order.customer.show',
            now()->addDays(30),
            ['orderNumber' => $order->order_number],
        );
    }

    public function stripeCancel(string $order_id)
    {
        DB::transaction(function () use ($order_id) {
            $order = Order::with('items')->lockForUpdate()->findOrFail($order_id);

            // Stripe puede volver a solicitar la URL de cancelación. No liberes
            // existencias ni cambies una orden que ya fue procesada.
            if (
                $order->payment_method !== 2 ||
                ! $order->stripe_payment_id ||
                $order->order_status !== 'pending' ||
                $order->payment_status !== 'pending'
            ) {
                return;
            }

            $this->checkoutService->restoreOrderStock($order);

            $order->update([
                'order_status' => 'cancelled',
                'payment_status' => 'cancelled',
            ]);
        });

        return redirect()->route('checkout.index')
            ->with('error', 'El pago fue cancelado. Por favor, intenta de nuevo.');
    }
}
