<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use Illuminate\Support\Facades\Log as Console;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected string $source;

    protected string $route;

    protected Product $model;

    public function __construct()
    {
        $this->source = 'Bussiness/Products/Pages/';
        $this->route = 'product.';
        $this->model = new Product;
    }

    public function index()
    {
        //
    }

    public function show(string $slug)
    {
        try {
            $product = $this->model
                ->with('variants', 'photos')
                ->where('is_active', true)
                ->where('slug', $slug)
                ->firstOrFail();

            $recommendedPackages = Package::with('products', 'photos', 'products.variants')
                ->where('is_active', true)
                ->whereHas('products', function ($query) use ($product) {
                    $query->where('products.id', $product->id);
                })
                ->inRandomOrder()
                ->take(3)
                ->get();

            $shortfall = 3 - $recommendedPackages->count();

            if ($shortfall > 0) {
                $extraPackages = Package::with(['products', 'photos', 'products.variants'])
                    ->where('is_active', true)
                    ->whereNotIn('id', $recommendedPackages->pluck('id')) // Evitamos duplicados
                    ->inRandomOrder()
                    ->take($shortfall)
                    ->get();

                $recommendedPackages = $recommendedPackages->merge($extraPackages);
            }

            if ($product->is_featured) {
                $title = 'Producto Destacado';
            } else {
                $title = 'Producto';
            }

            return Inertia::render("{$this->source}Show", [
                'title' => $title,
                'route' => $this->route,
                'productData' => new ProductResource($product),
                'recommendedPackages' => PackageResource::collection($recommendedPackages),
            ]);
        } catch (\Exception $e) {
            Console::error('Error al mostrar el producto: '.$e->getMessage());
        }
    }
}
