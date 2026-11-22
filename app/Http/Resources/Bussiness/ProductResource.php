<?php

namespace App\Http\Resources\Bussiness;

use App\Http\Resources\PhotoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// use App\Http\Resources\Bussiness\ProductCategoryResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cheapestVariant = $this->variants->sortBy('price')->first();

        return [
            'id' => $this->id,
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'limit_per_user' => $this->limit_per_user,
            'is_new' => $this->created_at->greaterThanOrEqualTo(now()->subDays(30)), // Considera un producto como "nuevo" si fue creado en los últimos 30 días
            'is_bundle' => false,
            'display_price' => $cheapestVariant ? $cheapestVariant->price : 0,
            'compare_at_price' => $cheapestVariant ? $cheapestVariant->compare_at_price : null,
            'stock' => $this->variants->sum('stock'),
            'has_multiple_options' => $this->variants->count() > 1,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            // 'category' => new ProductCategoryResource($this->whenLoaded('category')),
        ];
    }
}
