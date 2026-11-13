<?php

namespace Database\Seeders;

use App\Models\Bussiness\Coupon;
use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use App\Models\Bussiness\ProductVariant;
use App\Models\Catalogs\ProductCategory;
use App\Models\Photo;
use App\Models\Survey\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'description' => 'Administrador del sistema.']);
        $roleCustomer = Role::create(['name' => 'customer', 'guard_name' => 'web', 'description' => 'Cliente de la tienda en línea.']);
        $roleStaff = Role::create(['name' => 'staff', 'guard_name' => 'web', 'description' => 'Empleado de la tienda en línea.']);

        $admin = User::create([
            'name' => 'Admin',
            'lastname' => 'BullShop',
            'email' => 'sp@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($roleAdmin);
        $staff = User::create([
            'name' => 'Staff',
            'lastname' => 'BullShop',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);
        $staff->assignRole($roleStaff);

        $customer = User::create([
            'name' => 'Sergio',
            'lastname' => 'Peralta',
            'email' => 'serchypq04@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole($roleCustomer);

        $catHumanos = ProductCategory::create([
            'name' => 'Ropa y Accesorios (Humanos)',
            'slug' => Str::slug('Ropa y Accesorios Humanos'),
            'description' => 'Estilo oversize y streetwear para los dueños.',
        ]);

        $catPerros = ProductCategory::create([
            'name' => 'Equipo Táctico (Perros)',
            'slug' => Str::slug('Equipo Táctico Perros'),
            'description' => 'Pecheras, collares y correas de alta resistencia.',
        ]);

        // ==========================================
        // 2. CREAR PRODUCTOS (Cascarones Visuales)
        // ==========================================

        $hoodie = Product::create([
            'product_category_id' => $catHumanos->id,
            'name' => 'Hoodie Oversize "Barrel"',
            'slug' => Str::slug('Hoodie Oversize Barrel'),
            'description' => 'Algodón premium diseñado para el pecho ancho. Máxima comodidad.',
            'is_featured' => true,
        ]);

        $gorra = Product::create([
            'product_category_id' => $catHumanos->id,
            'name' => 'Gorra Snapback Bull',
            'slug' => Str::slug('Gorra Snapback Bull'),
            'description' => 'Gorra plana con logo bordado en 3D.',
        ]);

        $pechera = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Pechera Táctica K9',
            'slug' => Str::slug('Pechera Tactica K9'),
            'description' => 'Nylon 1000D resistente al agua con sistema MOLLE.',
            'is_featured' => true,
        ]);

        $collar = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Collar de Cuero Genuino',
            'slug' => Str::slug('Collar Cuero Genuino'),
            'description' => 'Doble capa de cuero con herrajes de acero inoxidable.',
        ]);

        $correa = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Correa Reforzada Anti-Tirones',
            'slug' => Str::slug('Correa Reforzada'),
            'description' => 'Largo de 1.5m con mango acolchado y resorte amortiguador.',
        ]);

        $plato = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Plato Acero Inoxidable Anti-Deslizante',
            'slug' => Str::slug('Plato Acero Inoxidable'),
            'description' => 'Base de goma, fácil de limpiar y no guarda olores.',
        ]);

        $cadena = Product::create([
            'product_category_id' => $catPerros->id,
            'name' => 'Cadena Acero Inoxidable',
            'slug' => Str::slug('Cadena Acero Inoxidable'),
            'description' => 'Cadena resistente al desgaste y a la corrosión.',
        ]);

        // ==========================================
        // 3. CREAR VARIANTES (Inventario Físico Real)
        // ==========================================

        // Variantes Hoodie
        ProductVariant::create(['product_id' => $hoodie->id, 'sku' => 'HOOD-BAR-M-BLK', 'name' => 'Talla M - Negro', 'attributes' => json_encode(['size' => 'M', 'color' => 'Negro']), 'price' => 1500, 'stock' => 15]);
        ProductVariant::create(['product_id' => $hoodie->id, 'sku' => 'HOOD-BAR-L-BLK', 'name' => 'Talla L - Negro', 'attributes' => json_encode(['size' => 'L', 'color' => 'Negro']), 'price' => 1500, 'stock' => 10]);
        ProductVariant::create(['product_id' => $hoodie->id, 'sku' => 'HOOD-BAR-L-GRY', 'name' => 'Talla L - Gris', 'attributes' => json_encode(['size' => 'L', 'color' => 'Gris']), 'price' => 1500, 'stock' => 5]);

        // Variantes Gorra (Unitalla)
        ProductVariant::create(['product_id' => $gorra->id, 'sku' => 'CAP-BULL-BLK', 'name' => 'Unitalla - Negro', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Negro']), 'price' => 450, 'stock' => 20]);
        ProductVariant::create(['product_id' => $gorra->id, 'sku' => 'CAP-BULL-RED', 'name' => 'Unitalla - Rojo', 'attributes' => json_encode(['size' => 'Unitalla', 'color' => 'Rojo']), 'price' => 450, 'stock' => 12]);

        // Variantes Pechera
        ProductVariant::create(['product_id' => $pechera->id, 'sku' => 'HAR-K9-M-CAMO', 'name' => 'Talla M - Camuflaje', 'attributes' => json_encode(['size' => 'M', 'color' => 'Camuflaje']), 'price' => 1200, 'stock' => 8]);
        ProductVariant::create(['product_id' => $pechera->id, 'sku' => 'HAR-K9-L-BLK', 'name' => 'Talla L - Negro', 'attributes' => json_encode(['size' => 'L', 'color' => 'Negro']), 'price' => 1250, 'stock' => 15]);

        // Variantes Collar
        ProductVariant::create(['product_id' => $collar->id, 'sku' => 'COL-LEA-M-BRN', 'name' => 'Talla M - Café', 'attributes' => json_encode(['size' => 'M', 'color' => 'Café']), 'price' => 600, 'stock' => 10]);
        ProductVariant::create(['product_id' => $collar->id, 'sku' => 'COL-LEA-L-BLK', 'name' => 'Talla L - Negro', 'attributes' => json_encode(['size' => 'L', 'color' => 'Negro']), 'price' => 650, 'stock' => 10]);

        // Variantes Correa & Plato
        ProductVariant::create(['product_id' => $correa->id, 'sku' => 'LEA-REI-15-BLK', 'name' => '1.5 Metros - Negro', 'attributes' => json_encode(['size' => '1.5m', 'color' => 'Negro']), 'price' => 350, 'stock' => 5]);
        ProductVariant::create(['product_id' => $plato->id, 'sku' => 'BOW-STE-2L', 'name' => 'Capacidad 2 Litros', 'attributes' => json_encode(['size' => '2L', 'color' => 'Acero']), 'price' => 400, 'stock' => 30]);

        // Variantes Cadena
        ProductVariant::create(['product_id' => $cadena->id, 'sku' => 'LEA-STE-15-BLK', 'name' => '1.5 Metros - Negro', 'attributes' => json_encode(['size' => '1.5m', 'color' => 'Negro']), 'price' => 350, 'stock' => 5]);

        // ==========================================
        // 4. CREAR PAQUETES (Promociones / Bundles)
        // ==========================================

        // Paquete 1: Humano
        $packOutfit = Package::create([
            'name' => 'Arma tu Outfit Bull',
            'slug' => Str::slug('Arma tu Outfit Bull'),
            'description' => 'Lleva 1 Hoodie Barrel y 1 Gorra Snapback a un precio especial.',
            'price' => 1700, // Precio con descuento
            'limit_per_user' => 3, // Promoción limitada
        ]);
        $packOutfit->products()->attach($hoodie->id, ['quantity' => 1]);
        $packOutfit->products()->attach($gorra->id, ['quantity' => 1]);

        // Paquete 2: Perro Paseo
        $packPaseo = Package::create([
            'name' => 'Kit Paseo Táctico',
            'slug' => Str::slug('Kit Paseo Tactico'),
            'description' => 'Todo lo que necesitas para dominar la calle: 1 Pechera y 1 Correa.',
            'price' => 1350,
            'compare_at_price' => 1500, // Precio original para mostrar descuento
            'limit_per_user' => 1, // Promoción limitada
            'is_featured' => true,
        ]);
        $packPaseo->products()->attach($pechera->id, ['quantity' => 1]);
        $packPaseo->products()->attach($correa->id, ['quantity' => 1]);

        // Paquete 3: Perro Full
        $packFull = Package::create([
            'name' => 'Starter Pack K9',
            'slug' => Str::slug('Starter Pack K9'),
            'description' => 'El combo definitivo: 1 Pechera, 1 Collar y 1 Plato.',
            'price' => 2000,
            'limit_per_user' => 2, // Promoción limitada
        ]);
        $packFull->products()->attach($pechera->id, ['quantity' => 1]);
        $packFull->products()->attach($collar->id, ['quantity' => 1]);
        $packFull->products()->attach($plato->id, ['quantity' => 1]);

        // ******************************************
        // ÁREA PARA IMÁGENES MANUALES (AWS S3)
        // ******************************************
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $hoodie->id,
            'usage' => 'gallery',
            'filename' => 'gorra_crema.jpg',
            'path' => 'Products/gorra_crema.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorra->id,
            'usage' => 'gallery',
            'filename' => 'gorra_crema.jpg',
            'path' => 'Products/gorra_crema.jpeg',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $gorra->id,
            'usage' => 'gallery',
            'filename' => 'gorra_azul.jpg',
            'path' => 'Products/gorra_azul.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $collar->id,
            'usage' => 'gallery',
            'filename' => 'gorra_azul.jpg',
            'path' => 'Products/gorra_azul.jpeg',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $correa->id,
            'usage' => 'gallery',
            'filename' => 'gorra_azul.jpg',
            'path' => 'Products/gorra_azul.jpeg',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $plato->id,
            'usage' => 'gallery',
            'filename' => 'gorra_rosa.jpg',
            'path' => 'Products/gorra_rosa.jpeg',
        ]);
        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $pechera->id,
            'usage' => 'gallery',
            'filename' => 'gorra_rosa.jpg',
            'path' => 'Products/gorra_rosa.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Product::class,
            'photoable_id' => $cadena->id,
            'usage' => 'gallery',
            'filename' => 'gorra_rosa.jpg',
            'path' => 'Products/gorra_rosa.jpeg',
        ]);

        // imagenes para los paquetes
        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packOutfit->id,
            'usage' => 'gallery',
            'filename' => 'gorra_crema.jpg',
            'path' => 'Products/gorra_crema.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packPaseo->id,
            'usage' => 'gallery',
            'filename' => 'gorra_azul.jpg',
            'path' => 'Products/gorra_azul.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packPaseo->id,
            'usage' => 'gallery',
            'filename' => 'gorra_crema.jpg',
            'path' => 'Products/gorra_crema.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packPaseo->id,
            'usage' => 'gallery',
            'filename' => 'gorra_azul.jpg',
            'path' => 'Products/gorra_azul.jpeg',
        ]);

        Photo::create([
            'photoable_type' => Package::class,
            'photoable_id' => $packFull->id,
            'usage' => 'gallery',
            'filename' => 'gorra_rosa.jpg',
            'path' => 'Products/gorra_rosa.jpeg',
        ]);

        Coupon::create([
            'code' => 'BULLSHOP10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_active' => true,
            'start_date' => now(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => 10,
            'used_count' => 0,
        ]);

        /* Descomentar para dev */

        $survey = Survey::create([
            'title' => 'Tu bulldog ya encontró a los suyos',
            'description' => '<h2><strong>Bienvenido al BullShop Club.</strong></h2>
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
                'question' => '¿A donde les enviamos su invitación?',
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
    }
}
