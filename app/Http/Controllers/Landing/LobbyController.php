<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
use Illuminate\Support\Facades\Cache;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use Inertia\Inertia;

class LobbyController extends Controller
{
    protected string $source;

    public function __construct()
    {
        $this->source = 'Landing/Pages/';
    }

    public function index()
    {
        $featuredPackages = Cache::remember('top_packages', 3600, function () {
            $packages =Package::where('is_featured', true)
                ->where('is_active', true)
                ->with('photos')->orderBy('created_at', 'desc')->limit(3)->get();

            $resource = PackageResource::collection($packages)->toJson();
            return json_decode($resource, true);
        });


        $featuredProducts = Cache::remember('top_products', 3600, function () {
            $products = Product::where('is_featured', true)
                ->WHERE('is_active', true)
                ->with('variants', 'photos')->orderBy('created_at', 'desc')->limit(3)->get();

            $resource = ProductResource::collection($products)->toJson();
            return json_decode($resource, true);
        });

        return Inertia::render("{$this->source}Lobby", [
            'featuredPackageData' => $featuredPackages,
            'featuredProductsData' => $featuredProducts,
        ]);
    }
}
