<?php

namespace App\Http\Controllers\Bussines;

use App\Http\Resources\Bussines\PackageResource;
use App\Http\Resources\Bussines\ProductResource;
use App\Http\Controllers\Controller;
use App\Models\Bussines\Package;
use App\Models\Bussines\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;


use Illuminate\Support\Facades\Log as Console;

class PackageController extends Controller
{
    protected String $source;
    protected String $route;
    protected Package $model;

    public function __construct()
    {
        $this->source = 'Bussines/Packages/Pages/';
        $this->route = 'package.';
        $this->model = new Package();
    }

    public function index()
    {
        //
    }

    public function show(String $slug)
    {
        try{
            $package = $this->model
                ->with('products')
                ->where('is_active', true)
                ->where ('slug', $slug)
                ->firstOrFail();

            $recommendedProducts = Product::where('is_active', true)
                ->whereDoesntHave('packages', function ($query) use ($package) {
                    $query->where('packages.id', $package->id);
                })
                ->inRandomOrder()
                ->take(4)
                ->get();

            if ($package->is_featured) {
                $title = 'Drop Destacado';
            } else{
                $title = 'Drop';
            }

                return Inertia::render("{$this->source}Show", [
                    'title' => $title,
                    'route' => $this->route,
                    'packageData' => new PackageResource($package),
                    'recommendedProducts' => ProductResource::collection($recommendedProducts),
                ]);

        } catch (\Exception $e) {
            Console::error('Error al mostrar el paquete: ' . $e->getMessage());
            return Inertia::render('Error', [
                'status' => 404,
            ]);
        }
    }
}
