<?php

namespace App\Http\Controllers\Landing;

use App\Http\Resources\Bussines\PackageResource;
use App\Http\Resources\Bussines\ProductResource;
use App\Http\Controllers\Controller;
use App\Models\Bussines\Package;
use App\Models\Bussines\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollectionController extends Controller
{
    protected String $source;

    public function __construct()
    {
        $this->source = 'Landing/Pages/Collection/';
    }

    public function index()
    {
        $packagesData = Package::where('is_active', true)->get();
        $productsData = Product::where('is_active', true)->get();

        Return Inertia::render("{$this->source}Index", [
            'title1' => 'Drop',
            'title2' => 'Actual',
            'description' => 'Equipamiento táctico y streetwear diseñado específicamente para la anatomía de tu bulldog. Ediciones limitadas.',
            'packagesData' => PackageResource::collection($packagesData),
            'productsData' => ProductResource::collection($productsData),
        ]);
    }
}
