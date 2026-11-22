<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bussiness\PackageResource;
use App\Http\Resources\Bussiness\ProductResource;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use Inertia\Inertia;

class CollectionController extends Controller
{
    protected string $source;

    public function __construct()
    {
        $this->source = 'Landing/Pages/Collection/';
    }

    public function index()
    {
        $packagesData = Package::where('is_active', true)
            ->with('photos')
            ->orderBy('created_at', 'desc')
            ->get();

        $productsData = Product::where('is_active', true)
            ->with(['variants', 'photos']) // CRÍTICO: Cargar variants para calcular stock/precio
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'title1' => 'Drop',
            'title2' => 'Actual',
            'description' => 'Equipamiento táctico y streetwear diseñado específicamente para la anatomía de tu bulldog. Ediciones limitadas.',
            'packagesData' => PackageResource::collection($packagesData),
            'productsData' => ProductResource::collection($productsData),
        ]);
    }
}
