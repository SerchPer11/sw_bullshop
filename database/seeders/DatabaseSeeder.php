<?php

namespace Database\Seeders;

use App\Models\Bussiness\Coupon;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use App\Models\Bussiness\ProductVariant;
use App\Models\Catalogs\ProductCategory;
use App\Models\Photo;
use App\Models\Survey\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\ProductSeeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $catHumanos = ProductCategory::create([
            'name' => 'Ropa y Accesorios (Humanos)',
            'slug' => Str::slug('Ropa y Accesorios Humanos'),
            'description' => 'Accesorios cotidianos y gorras edición limitada para Dog Moms y Dog Dads.',
        ]);

        $catPerros = ProductCategory::create([
            'name' => 'Accesorios (Perrhijos)',
            'slug' => Str::slug('Accesorios Perrhijos'),
            'description' => 'Gorras y accesorios diseñados especialmente para tu bulldog.',
        ]);

        $catCotidiano = ProductCategory::create([
            'name' => 'Detalles para lo Cotidiano',
            'slug' => Str::slug('Detalles para lo Cotidiano'),
            'description' => 'Lanyards, tote bags y stickers oficiales de la familia BullShop.',
        ]);

        // ==========================================
        // 2. CREAR PRODUCTOS (Cascarones Visuales)
        // ==========================================

        $gorraHumano = Product::create([
            'product_category_id' => $catHumanos->id,
            'name' => 'Gorra BullShop Porfirio (Para Ti)',
            'slug' => Str::slug('Gorra BullShop Porfirio Para Ti'),
            'description' => 'Gorra edición especial Reto Guinness para Dog Mom / Dog Dad. Haz match con tu perrhijo.',
            'is_featured' => true,
        ]);

        $gorraPerro = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Gorra BullShop Porfirio (Para tu Perrhijo)',
            'slug' => Str::slug('Gorra BullShop Porfirio Para tu Perrhijo'),
            'description' => 'Gorra para bulldog con cierre de velcro y mentonera bordada BullShop.',
            'is_featured' => true,
        ]);

        $toteBag = Product::create([
            'product_category_id' => $catCotidiano->id,
            'name' => 'Tote Bag BullShop',
            'slug' => Str::slug('Tote Bag BullShop'),
            'description' => 'Bolsa tote de lona resistente en dos diseños exclusivos: Porfirio y BullShop.',
            'is_featured' => true,
        ]);

        $lanyard = Product::create([
            'product_category_id' => $catCotidiano->id,
            'name' => 'Lanyard Oficial BullShop',
            'slug' => Str::slug('Lanyard Oficial BullShop'),
            'description' => 'Lanyard oficial edición evento disponible en azul y fucsia.',
        ]);

        $stickers = Product::create([
            'product_category_id' => $catCotidiano->id,
            'name' => 'Set de Stickers BullShop',
            'slug' => Str::slug('Set de Stickers BullShop'),
            'description' => 'Set de stickers coleccionables edición lanzamiento BullShop.',
        ]);

        $gumms = Product::create([
            'product_category_id' => $catCotidiano->id,
            'name' => 'Gomitas BullShop',
            'slug' => Str::slug('Gomitas BullShop'),
            'description' => 'Gomitas de gelatina edición especial BullShop. Contenido neto: 100g.',
        ]);

        // ==========================================
        // 3. CREAR VARIANTES (Inventario Físico Real)
        // ==========================================

        // Variantes Hoodie
        ProductVariant::create(['product_id' => $gorraHumano->id, 'sku' => 'CAP-HUM-ROS', 'name' => 'Unitalla - Rosa', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Rosa']), 'price' => 560, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraHumano->id, 'sku' => 'CAP-HUM-AZU', 'name' => 'Unitalla - Azul', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Azul']), 'price' => 560, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraHumano->id, 'sku' => 'CAP-HUM-VIN', 'name' => 'Unitalla - Vino', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Vino']), 'price' => 560, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraHumano->id, 'sku' => 'CAP-HUM-RBG', 'name' => 'Unitalla - Rosa con Beige', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Rosa con Beige']), 'price' => 560, 'stock' => 10]);

        // Variantes Gorra Perrhijo ($470 c/u) - Total: 40 piezas (para los 40 paquetes Star Parker)
        ProductVariant::create(['product_id' => $gorraPerro->id, 'sku' => 'CAP-DOG-ROS', 'name' => 'Ajustable - Rosa', 'attributes' => json_encode(['size' => 'Ajustable', 'color' => 'Rosa']), 'price' => 470, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraPerro->id, 'sku' => 'CAP-DOG-AZU', 'name' => 'Ajustable - Azul', 'attributes' => json_encode(['size' => 'Ajustable', 'color' => 'Azul']), 'price' => 470, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraPerro->id, 'sku' => 'CAP-DOG-VIN', 'name' => 'Ajustable - Vino', 'attributes' => json_encode(['size' => 'Ajustable', 'color' => 'Vino']), 'price' => 470, 'stock' => 10]);
        ProductVariant::create(['product_id' => $gorraPerro->id, 'sku' => 'CAP-DOG-RBG', 'name' => 'Ajustable - Rosa con Beige', 'attributes' => json_encode(['size' => 'Ajustable', 'color' => 'Rosa con Beige']), 'price' => 470, 'stock' => 10]);

        // Variantes Tote Bag ($450 c/u) - Total: 55 piezas (40 Star Parker + 15 Miembro Oficial)
        ProductVariant::create(['product_id' => $toteBag->id, 'sku' => 'TOT-POR-BEI', 'name' => 'Diseño Porfirio', 'attributes' => json_encode(['design' => 'Porfirio', 'color' => 'Beige']), 'price' => 450, 'stock' => 28]);
        ProductVariant::create(['product_id' => $toteBag->id, 'sku' => 'TOT-BUL-BEI', 'name' => 'Diseño BullShop', 'attributes' => json_encode(['design' => 'BullShop', 'color' => 'Beige']), 'price' => 450, 'stock' => 27]);

        // Variantes Lanyard ($130 c/u) - Total: 70 piezas (ajusta si son 70 extra aparte de los 55 de paquetes = 125)
        ProductVariant::create(['product_id' => $lanyard->id, 'sku' => 'LAN-BUL-AZU', 'name' => 'Color Azul', 'attributes' => json_encode(['color' => 'Azul']), 'price' => 130, 'stock' => 35]);
        ProductVariant::create(['product_id' => $lanyard->id, 'sku' => 'LAN-BUL-FUC', 'name' => 'Color Fucsia', 'attributes' => json_encode(['color' => 'Fucsia']), 'price' => 130, 'stock' => 35]);

        // Variante Set de Stickers ($80 c/u) - Total: 200 piezas
        ProductVariant::create(['product_id' => $stickers->id, 'sku' => 'STK-BUL-SET1', 'name' => 'Set Edición Lanzamiento', 'attributes' => json_encode(['edition' => 'Lanzamiento']), 'price' => 80, 'stock' => 200]);

        // Variantes Gomitas ($50 c/u) - Total: 89 piezas
        ProductVariant::create(['product_id' => $gumms->id, 'sku' => 'GUM-BUL-CHE', 'name' => 'Cherry Eye (Rojo)', 'attributes' => json_encode(['weight' => '100g']), 'price' => 50, 'stock' => 8]);
        ProductVariant::create(['product_id' => $gumms->id, 'sku' => 'GUM-BUL-FRE', 'name' => 'Frenchie (Amarillo)', 'attributes' => json_encode(['weight' => '100g']), 'price' => 50, 'stock' => 8]);
        ProductVariant::create(['product_id' => $gumms->id, 'sku' => 'GUM-BUL-TAL', 'name' => 'Talmmys (Naranja)', 'attributes' => json_encode(['weight' => '100g']), 'price' => 50, 'stock' => 19]);
        ProductVariant::create(['product_id' => $gumms->id, 'sku' => 'GUM-BUL-MER', 'name' => 'Merle (Colores)', 'attributes' => json_encode(['weight' => '100g']), 'price' => 50, 'stock' => 37]);
        ProductVariant::create(['product_id' => $gumms->id, 'sku' => 'GUM-BUL-BVS', 'name' => 'BullVSdog (Enchiladas)', 'attributes' => json_encode(['weight' => '100g']), 'price' => 50, 'stock' => 17]);
        // ==========================================
        // 4. CREAR PAQUETES (Promociones / Bundles)
        // ==========================================

        // Paquete 1: Humano
        $packStarParker = Package::create([
            'name' => 'BullShop Star Parker',
            'slug' => Str::slug('BullShop Star Parker'),
            'description' => 'El kit completo del evento: Set de stickers, Tote Bag a elegir, Lanyard (azul o fucsia) y tu set de gorras match (para ti y tu perrhijo).',
            'price' => 1521,
            'compare_at_price' => 1690,
            'limit_per_user' => 2,
            'is_featured' => true,
        ]);
        $packStarParker->products()->attach($stickers->id, ['quantity' => 1]);
        $packStarParker->products()->attach($toteBag->id, ['quantity' => 1]);
        $packStarParker->products()->attach($lanyard->id, ['quantity' => 1]);
        $packStarParker->products()->attach($gorraHumano->id, ['quantity' => 1]);
        $packStarParker->products()->attach($gorraPerro->id, ['quantity' => 1]);

        // Paquete 2: BullShop Miembro Oficial (15 paquetes disponibles)
        // Suma individual: $80 + $450 + $130 = $660 (-10% = $594)
        $packMiembroOficial = Package::create([
            'name' => 'BullShop Miembro Oficial',
            'slug' => Str::slug('BullShop Miembro Oficial'),
            'description' => 'Lleva lo esencial de la familia BullShop: Set de stickers, Tote Bag a elegir y Lanyard (azul o fucsia).',
            'price' => 594,
            'compare_at_price' => 660,
            'limit_per_user' => 2,
            'is_featured' => false,
        ]);
        $packMiembroOficial->products()->attach($stickers->id, ['quantity' => 1]);
        $packMiembroOficial->products()->attach($toteBag->id, ['quantity' => 1]);
        $packMiembroOficial->products()->attach($lanyard->id, ['quantity' => 1]);

        // ******************************************
        // ÁREA PARA IMÁGENES MANUALES (AWS S3)
        // ******************************************

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_prof.webp',
            'path' => 'Products/h_prof.webp',
        ]);

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_prof_pink_red.webp',
            'path' => 'Products/h_prof_pink_red.webp',
        ]);

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_porf_pink.webp',
            'path' => 'Products/h_porf_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_porf_blue_pink.webp',
            'path' => 'Products/h_porf_blue_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_porf_blue_2.webp',
            'path' => 'Products/h_porf_blue_2.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_porf_blue.webp',
            'path' => 'Products/h_porf_blue.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_pink.webp',
            'path' => 'Products/h_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraHumano->id,
            'usage' => 'gallery',
            'filename' => 'h_prof.webp',
            'path' => 'Products/h_pink_h.webp',
        ]);
        // GORRA PERRO
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraPerro->id,
            'usage' => 'gallery',
            'filename' => 'set_gorr.webp',
            'path' => 'Products/set_gorr.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraPerro->id,
            'usage' => 'gallery',
            'filename' => 'b_prof.webp',
            'path' => 'Products/b_prof.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraPerro->id,
            'usage' => 'gallery',
            'filename' => 'b_porf_p_b.webp',
            'path' => 'Products/b_porf_p_b.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorraPerro->id,
            'usage' => 'gallery',
            'filename' => 'b_prof_pink_blue.webp',
            'path' => 'Products/b_prof_pink_blue.webp',
        ]);

        //totebaG
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_black.webp',
            'path' => 'Products/tote_black.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_back_2.webp',
            'path' => 'Products/tote_back_2.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_back_3.webp',
            'path' => 'Products/tote_back_3.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_back_4.webp',
            'path' => 'Products/tote_back_4.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_back_5.webp',
            'path' => 'Products/tote_back_5.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_cream.webp',
            'path' => 'Products/tote_cream.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_cream_2.webp',
            'path' => 'Products/tote_cream_2.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $toteBag->id,
            'usage' => 'gallery',
            'filename' => 'tote_cream_3.webp',
            'path' => 'Products/tote_cream_3.webp',
        ]);

        //LANYARD
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue_pink.webp',
            'path' => 'Products/landyard_blue_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue.webp',
            'path' => 'Products/landyard_blue.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue_2.webp',
            'path' => 'Products/landyard_blue_2.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue_3.webp',
            'path' => 'Products/landyard_blue_3.webp',
        ]);
        
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_pink.webp',
            'path' => 'Products/landyard_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $lanyard->id,
            'usage' => 'gallery',
            'filename' => 'landyard_pink_2.webp',
            'path' => 'Products/landyard_pink_2.webp',
        ]);

        // GUMMS
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'gums.webp',
            'path' => 'Products/gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'gums_gums.webp',
            'path' => 'Products/gums_gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'frenchie_gums.webp',
            'path' => 'Products/frenchie_gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'slug_gums.webp',
            'path' => 'Products/slug_gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'tammies_gums.webp',
            'path' => 'Products/tammies_gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'merle_gums.webp',
            'path' => 'Products/merle_gums.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gumms->id,
            'usage' => 'gallery',
            'filename' => 'cherry_gums.webp',
            'path' => 'Products/cherry_gums.webp',
        ]);

        // STICKERS
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $stickers->id,
            'usage' => 'gallery',
            'filename' => 'stickers.webp',
            'path' => 'Products/stickers.webp',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $stickers->id,
            'usage' => 'gallery',
            'filename' => 'stickers_2.webp',
            'path' => 'Products/stickers_2.webp',
        ]);

        // ==========================================
        // IMÁGENES PARA LOS PAQUETES (Reutilizando S3)
        // ==========================================

        // Paquete: BullShop Miembro Oficial (Tote Bag + Lanyard + Stickers)
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'tote_black.webp',
            'path' => 'Products/tote_black.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'tote_cream.webp',
            'path' => 'Products/tote_cream.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue_pink.webp',
            'path' => 'Products/landyard_blue_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue.webp',
            'path' => 'Products/landyard_blue.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'landyard_pink.webp',
            'path' => 'Products/landyard_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'stickers.webp',
            'path' => 'Products/stickers.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packMiembroOficial->id,
            'usage' => 'gallery',
            'filename' => 'stickers_2.webp',
            'path' => 'Products/stickers_2.webp',
        ]);

        // Paquete: BullShop Star Parker (Gorras + Tote Bag + Lanyard + Stickers)
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'duo_blue.webp',
            'path' => 'Products/h_prof.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'h_prof.webp',
            'path' => 'Products/h_prof.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'b_prof.webp',
            'path' => 'Products/b_prof.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'h_porf_blue_pink.webp',
            'path' => 'Products/h_porf_blue_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'b_prof_pink_blue.webp',
            'path' => 'Products/b_prof_pink_blue.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'tote_black.webp',
            'path' => 'Products/tote_black.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'tote_cream.webp',
            'path' => 'Products/tote_cream.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'landyard_blue_pink.webp',
            'path' => 'Products/landyard_blue_pink.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'stickers.webp',
            'path' => 'Products/stickers.webp',
        ]);
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packStarParker->id,
            'usage' => 'gallery',
            'filename' => 'stickers_2.webp',
            'path' => 'Products/stickers_2.webp',
        ]);

        Coupon::create([
            'code' => 'BULLSHOP10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_active' => true,
            'start_date' => now(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => 40,
            'used_count' => 0,
            'max_uses_per_user' => 1,
        ]);

        Coupon::create([
            'code' => 'BSCLUB05',
            'discount_type' => 'percentage',
            'discount_value' => 5,
            'is_active' => true,
            'start_date' => now(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => 0,
            'used_count' => 0,
            'max_uses_per_user' => 1,
        ]);

        /* Descomentar para dev */

        $survey = Survey::create([
            'title' => '¡Bienvenido al BullShop Club!',
            'description' => '
                <p>El espacio diseñado para los bulldogs con personalidad y los humanos que los respaldan.</p>
                <p>Queremos conocer a tu equipo. Preséntanos a tu perro y asegura tu lugar desde el día uno.</p>',
            'is_active' => true,
        ]);

        $survey->questions()->createMany([
            [
                'type' => 'title',
                'question' => 'Queremos conocer a tu bulldog y a ti',
                'icon' => 'HeartHandshake',
                'options' => null,
                'order' => 0,
            ],
            [
                'type' => 'text',
                'question' => '¿Como se llama tu perrhijo?',
                'placeholder' => 'Magno Chabelo',
                'options' => null,
                'is_required' => true,
                'order' => 1,
                'code' => 'bulldog',
                'validation_rules' => 'required|string|max:255',
                'ui_config' => [
                    'validation' => [
                        'pattern' => 'alpha',
                    ],
                ],
            ],
            [
                'type' => 'radio',
                'question' => '¿Quién nos lo esta presentando?',
                'placeholder' => '¿Mami o papi?',
                'options' => ['Su mami', 'Su papi'],
                'is_required' => true,
                'order' => 2,
                'code' => 'owner',
                'validation_rules' => 'required',
            ],
            [
                'type' => 'checkbox',
                'question' => 'Cuando sales con {{ bulldog }} ¿qué te gustaría tener mejor resuelto?',
                'placeholder' => 'Elige hasta 2 opciones',
                'options' => [
                    'Que vaya cómodo y con estilo — gorras que le queden bien y ropa streetwear.',
                    'Pasear con más seguridad y control — arneses, pecheras y correas resistentes.',
                    'Llevar todo sin complicarte — agua, termo, bolsas para desechos y mochila.',
                ],
                'is_required' => true,
                'order' => 3,
                'code' => 'style',
                'validation_rules' => 'required|array|max:2',
                'ui_config' => ['maxSelections' => 2],
            ],
            [
                'type' => 'title',
                'question' => '¿A donde les enviamos su cupón?',
                'icon' => 'Mailbox',
                'options' => null,
                'order' => 4,
            ],
            [
                'type' => 'text',
                'question' => 'Nombre(s) del humano',
                'placeholder' => 'Juanito Nepomuceno',
                'options' => null,
                'is_required' => true,
                'order' => 5,
                'code' => 'owner_name',
                'validation_rules' => 'required|string|max:255',
                'ui_config' => [
                    'validation' => [
                        'pattern' => 'alpha',
                    ],
                ],
            ],
            [
                'type' => 'text',
                'question' => 'Apellido(s) del humano',
                'placeholder' => 'Pérez García',
                'options' => null,
                'is_required' => true,
                'order' => 6,
                'code' => 'owner_lastname',
                'validation_rules' => 'required|string|max:255',
                'ui_config' => [
                    'validation' => [
                        'pattern' => 'alpha',
                    ],
                ],
            ],
            [
                'type' => 'email',
                'question' => 'Correo electrónico',
                'placeholder' => 'correo@ejemplo.com',
                'options' => null,
                'is_required' => true,
                'order' => 7,
                'code' => 'owner_email',
                'validation_rules' => 'required|email|max:255',
            ],
            [
                'type' => 'tel',
                'question' => 'Número de teléfono',
                'placeholder' => '123-456-7890',
                'options' => null,
                'is_required' => true,
                'order' => 8,
                'code' => 'owner_phone',
                'validation_rules' => 'required|string|regex:/^\\d{10}$/',
                'ui_config' => [
                    'formatter' => 'mx-phone',
                    'inputAttrs' => [
                        'autocomplete' => 'tel',
                        'inputmode' => 'numeric',
                    ],
                ],
            ],
            /*
            [
                'type' => 'radio',
                'question' => '¿Qué tipo de producto te interesa más para tu bulldog?',
                'options' => ['Streetwear (Playeras, hoodies)', 'Accesorios (Gorras, bandanas)'],
                'is_required' => true,
                'order' => 3,
            ],
            [
                'type' => 'checkbox',
                'question' => '¿Qué colores prefieres?',
                'options' => ['Negro', 'Blanco', 'Gris', 'Rojo', 'Azul', 'Verde', 'Amarillo', 'Rosa', 'Morado', 'Naranja'],
                'is_required' => true,
                'order' => 4,
            ],
            [
                'type' => 'title',
                'question' => '¿Cuantos bulldogs tienes?',
                'options' => null,
                'is_required' => true,
                'order' => 5,
            ],
            [
                'type' => 'repeater',
                'question' => 'Registra a tus bulldogs',
                'options' => [
                    ['field' => 'nombre', 'placeholder' => 'Nombre de tu bulldog', 'type' => 'text', 'is_required' => true],
                    ['field' => 'talla', 'placeholder' => 'Talla estimada', 'type' => 'radio', 'choices' => ['S', 'M', 'L', 'XL'], 'is_required' => true],
                    ['field' => 'bull_birth', 'placeholder' => 'Fecha de nacimiento', 'type' => 'date', 'is_required' => false],
                ],
                'is_required' => false,
                'order' => 6,
            ],
            [
                'type' => 'textarea',
                'question' => '¿Tienes alguna pregunta o comentario?',
                'options' => null,
                'is_required' => false,
                'order' => 7,
            ],*/
        ]);
        $this->call([
            PermissionsSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
