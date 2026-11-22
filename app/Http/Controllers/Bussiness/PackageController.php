<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use Inertia\Inertia;

class PackageController extends Controller
{
    protected string $source;

    protected string $route;

    protected Package $model;

    public function __construct()
    {
        $this->source = 'Bussiness/Packages/Pages/';
        $this->route = 'package.';
        $this->model = new Package;
    }

    public function index()
    {
        //
    }

    public function show(string $slug)
    {
        try {
            $package = $this->model
                ->with('products', 'photos', 'products.variants')
                ->where('is_active', true)
                ->where('slug', $slug)
                ->firstOrFail();

            $recommendedProducts = Product::with('variants', 'photos')
                ->where('is_active', true)
                ->whereDoesntHave('packages', function ($query) use ($package) {
                    $query->where('packages.id', $package->id);
                })
                ->inRandomOrder()
                ->take(3)
                ->get();

            if ($package->is_featured) {
                $title = 'Drop Destacado';
            } else {
                $title = 'Drop';
            }

            return Inertia::render("{$this->source}Show", [
                'title' => $title,
                'route' => $this->route,
                'packageData' => new PackageResource($package),
                'recommendedProducts' => ProductResource::collection($recommendedProducts),
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Error', [
                'status' => 404,
            ]);
        }
    }
}
