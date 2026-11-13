<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
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
        $featuredPackages = Package::where('is_featured', true)
            ->WHERE('is_active', true)
            ->with('photos');

        $featuredPackage = $featuredPackages->orderBy('created_at', 'desc')->take(5)->get();

        $featuredProducts = Product::where('is_featured', true)
            ->WHERE('is_active', true)
            ->with('variants', 'photos');

        $featuredProducts = $featuredProducts->orderBy('created_at', 'desc')->take(5)->get();

        return Inertia::render("{$this->source}Lobby", [
            'featuredPackageData' => PackageResource::collection($featuredPackage),
            'featuredProductsData' => ProductResource::collection($featuredProducts),
        ]);
    }
}
