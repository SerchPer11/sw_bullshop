<?php

namespace App\Http\Resources\Bussines;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_category_id' => $this->product_category_id,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'attributes' => $this->attributes,
            'is_new' => $this->created_at->greaterThanOrEqualTo(now()->subDays(30)), // Considera un producto como "nuevo" si fue creado en los últimos 30 días
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
