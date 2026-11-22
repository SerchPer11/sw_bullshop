<?php

namespace App\Http\Resources\Bussiness;

use App\Http\Resources\PhotoResource;
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
        $calculatedStock = $this->products->map(function ($product) {
            $totalProductStock = $product->variants->sum('stock');
            $requiredQuantity = $product->pivot->quantity ?? 1;

            return floor($totalProductStock / $requiredQuantity);
        })->min() ?? 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'limit_per_user' => $this->limit_per_user,
            'is_new' => $this->created_at->greaterThanOrEqualTo(now()->subDays(30)), // Considera un paquete como "nuevo" si fue creado en los últimos 30 días
            'is_bundle' => true,
            'display_price' => $this->price,
            'stock' => $calculatedStock,
            'has_multiple_options' => true,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'variants' => ProductVariantResource::collection($product->variants),
                        'photos' => PhotoResource::collection(
                            $product->relationLoaded('photos') ? $product->photos : collect()
                        ),
                    ];
                });
            }),
        ];
    }
}
