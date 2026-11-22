<?php

namespace App\Http\Services;

use App\Http\Resources\Bussiness\OrderResource;
use App\Models\Bussiness\Order;
use Inertia\Inertia;

class TrackOrderService
{
    private function orderRelations(): array
    {
        return [
            'coupon',
            'items.product.variants',
            'items.product.photos',
            'items.package.photos',
            'items.package.products.variants',
            'items.package.products.photos',
        ];
    }

    public function getOrdersAuth(string $userId, string $routeName, string $source, array $filters = [])
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $orders = Order::query()
            ->with($this->orderRelations())
            ->where('user_id', $userId)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('order_status', 'like', "%{$search}%")
                        ->orWhere('payment_status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 8))
            ->withQueryString();

        return Inertia::render("{$source}Index", [
            'title' => 'Rastreo de pedido',
            'route' => $routeName,
            'orders' => OrderResource::collection($orders),
            'order' => null,
            'isAuthenticated' => true,
            'filters' => $filters,
        ]);
    }

    public function getOrderAuth(string $userId, string $orderNumber): ?Order
    {
        return Order::with($this->orderRelations())
            ->where('user_id', $userId)
            ->where('order_number', $orderNumber)
            ->first();
    }

    public function getAllOrders(array $filters = [])
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return Order::query()
            ->with($this->orderRelations())
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('payment_status', 'like', "%{$search}%")
                        ->orWhere('order_status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 8))
            ->withQueryString();
    }

    public function getOrderByNumber(string $orderNumber): ?Order
    {
        return Order::with($this->orderRelations())
            ->where('order_number', $orderNumber)
            ->first();
    }

    public function getOrdersGuest(string $orderNumber, string $email): ?Order
    {
        $order = Order::with($this->orderRelations())
            ->where('order_number', $orderNumber)
            ->where('customer_email', $email)
            ->first();

        return $order;
    }
}
