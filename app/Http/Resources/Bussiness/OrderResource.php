<?php

namespace App\Http\Resources\Bussiness;

use App\Traits\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    use DateFormat;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $saleSubtotal = $this->relationLoaded('items')
            ? $this->items->sum(fn ($item) => $item->unit_price * $item->quantity)
            : null;
        $productDiscount = $saleSubtotal === null
            ? null
            : max(0, $this->subtotal - $saleSubtotal);
        $couponDiscount = $productDiscount === null
            ? null
            : max(0, $this->discount_amount - $productDiscount);

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'shipping_address' => [
                'street' => $this->street,
                'outdoor_number' => $this->outdoor_number,
                'indoor_number' => $this->indoor_number,
                'colony' => $this->colony,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
            ],
            'shipping_cost' => $this->shipping_cost,
            'discount_amount' => $this->discount_amount,
            'product_discount_amount' => $productDiscount,
            'coupon_discount_amount' => $couponDiscount,
            'coupon_code' => $this->whenLoaded('coupon', fn () => $this->coupon->code),
            'subtotal' => $this->subtotal,
            'notes' => $this->notes,
            'is_gift' => $this->is_gift,
            'gift_message' => $this->gift_message,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'order_date' => $this->textFormatDate($this->created_at),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
