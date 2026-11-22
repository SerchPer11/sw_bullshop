<?php

namespace App\Http\Resources\Bussiness;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->attributes ?? [];

        while (is_string($attributes)) {
            $decoded = json_decode($attributes, true);

            if (json_last_error() !== JSON_ERROR_NONE || $decoded === $attributes) {
                $attributes = [];
                break;
            }

            $attributes = $decoded;
        }

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'attributes' => $attributes,
            'is_new' => $this->created_at->greaterThanOrEqualTo(now()->subDays(30)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
