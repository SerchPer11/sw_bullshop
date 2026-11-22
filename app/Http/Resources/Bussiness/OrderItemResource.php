<?php

namespace App\Http\Resources\Bussiness;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributesSelected = $this->attributes_selected ?? [];

        while (is_string($attributesSelected)) {
            $decoded = json_decode($attributesSelected, true);

            if (json_last_error() !== JSON_ERROR_NONE || $decoded === $attributesSelected) {
                $attributesSelected = [];
                break;
            }

            $attributesSelected = $decoded;
        }

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'package_id' => $this->package_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'attributes_selected' => $attributesSelected,
            'products' => $this->when($this->product_id !== null, function () {
                return new ProductResource($this->product);
            }),
            'packages' => $this->when($this->package_id !== null, function () {
                return new PackageResource($this->package);
            }),
            'selected_package_variants' => $this->when(
                $this->package_id !== null,
                fn () => $attributesSelected['selections'] ?? []
            ),
        ];
    }
}
