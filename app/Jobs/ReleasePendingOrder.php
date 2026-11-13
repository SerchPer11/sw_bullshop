<?php

namespace App\Jobs;

use App\Http\Services\CheckoutService;
use App\Models\Bussiness\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ReleasePendingOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(CheckoutService $checkoutService): void
    {
        DB::transaction(function () use ($checkoutService): void {
            $order = Order::with('items')
                ->lockForUpdate()
                ->find($this->orderId);

            if (! $order || $order->payment_status !== 'pending') {
                return;
            }

            $checkoutService->restoreOrderStock($order);

            $order->update([
                'payment_status' => 'cancelled',
                'order_status' => 'cancelled',
            ]);
        });
    }
}
