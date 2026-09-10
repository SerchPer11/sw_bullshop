<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Catalogs\ProductCategory;
use App\Models\Bussines\Product;
use App\Models\Bussines\Package;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */

        ProductCategory::create([
            'name' => 'Joyeria',
            'description' => 'Productos de joyería.',
            'slug' => 'joyeria',
            'is_active' => true,
        ]);
        ProductCategory::create([
            'name' => 'Accesorios',
            'description' => 'Productos de accesorios.',
            'slug' => 'accesorios',
            'is_active' => true,
        ]);
        ProductCategory::create([
            'name' => 'Ropa',
            'description' => 'Productos de ropa.',
            'slug' => 'ropa',
            'is_active' => true,
        ]);

        $hoodie = Product::create([
        'product_category_id' => 3, // Asumiendo que tienes una categoría
        'sku' => 'HOODIE-BARREL-01',
        'name' => 'Hoodie Oversize "Barrel"',
        'slug' => 'hoodie-oversize-barrel',
        'description' => 'Algodón premium para el pecho de barril.',
        'price' => 1500.00,
        'stock' => 20,
        ]);

        $collar = Product::create([
            'product_category_id' => 1,
            'sku' => 'COLLAR-LATON-28',
            'name' => 'Collar de Latón 28mm',
            'slug' => 'collar-laton-28mm',
            'description' => 'Broches metálicos indestructibles.',
            'price' => 950.00,
            'stock' => 15,
        ]);

        $gorra = Product::create([
            'product_category_id' => 2,
            'sku' => 'GORRA-ALG',
            'name' => 'Gorra de Algodón',
            'slug' => 'gorra-algodon',
            'description' => 'Gorra de algodón para el día a día.',
            'price' => 500.00,
            'stock' => 15,
        ]);

        // 2. Creamos el Paquete Destacado (El que saldrá en el Hero)
        $duetto = Package::create([
            'sku' => 'SET-DUETTO',
            'name' => 'Set Street Duetto',
            'slug' => 'set-street-duetto',
            'description' => 'El match perfecto entre tú y tu bulldog.',
            'price' => 4000.00,
            'compare_at_price' => 4500.00, // Mostramos que tiene descuento
            'stock' => 5,
            'is_featured' => false,
        ]);

        $duetto->products()->attach([$hoodie->id, $collar->id]);

        $trio = Package::create([
            'sku' => 'SET-TRIO',
            'name' => 'Set Street Trio',
            'slug' => 'set-street-trio',
            'description' => 'El match perfecto entre tú y tu bulldog.',
            'price' => 3850.00,
            'compare_at_price' => 4500.00, // Mostramos que tiene descuento
            'stock' => 2,
            'is_featured' => true, // <--- ¡ESTA ES LA MAGIA DEL HERO!
        ]);

        $trio->products()->attach([$hoodie->id, $collar->id, $gorra->id]);


    }
}
