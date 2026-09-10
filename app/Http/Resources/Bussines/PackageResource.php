<?php

namespace App\Http\Resources\Bussines;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'stock' => $this->stock,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'is_new' => $this->created_at->greaterThanOrEqualTo(now()->subDays(30)), // Considera un paquete como "nuevo" si fue creado en los últimos 30 días
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
