<?php

namespace App\Http\Controllers\Landing;

use App\Http\Resources\Bussines\ProductResource;
use App\Http\Resources\Bussines\PackageResource;
use App\Http\Controllers\Controller;
use App\Models\Bussines\Product;
use App\Models\Bussines\Package;
use Inertia\Inertia;
class LobbyController extends Controller
{
    protected String $source;
    
    public function __construct()
    {
        $this->source = 'Landing/Pages/';
    }

    public function index()
    {
        $featuredPackage = Package::where('is_featured', true)
            ->WHERE('is_active', true)
            ->with('products')->first();

        $featuredProducts = Product::where('is_featured', true)
            ->WHERE('is_active', true)
            ->get();
            
        return Inertia::render("{$this->source}Lobby",[
            'featuredPackageData' => new PackageResource($featuredPackage),
            'featuredProductsData' => ProductResource::collection($featuredProducts),
        ]);
    }
}
