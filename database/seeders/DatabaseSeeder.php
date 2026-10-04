<?php

namespace Database\Seeders;

use App\Models\Bussiness\Package;
use App\Models\Bussiness\Product;
use App\Models\Catalogs\ProductCategory;
use App\Models\Survey\Survey;
use App\Models\Users\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        /*
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
        Product::create([
            'product_category_id' => 1,
            'sku' => 'Pulseras-Laton-15',
            'name' => 'Pulseras de Latón 15mm',
            'slug' => 'pulseras-laton-15mm',
            'description' => 'Pulseras metalicas indestructibles.',
            'price' => 950.00,
            'stock' => 15,
        ]);

        Product::create([
            'product_category_id' => 2,
            'sku' => 'Pin-Bull-01',
            'name' => 'Pin de Bull',
            'slug' => 'pin-bull-01',
            'description' => 'Un pin con el logo de Bull.',
            'price' => 100.00,
            'stock' => 15,
            'is_featured' => true,
            'limit_per_user' => 2, // Limitamos a 1 por usuario
        ]);

        Product::create([
            'product_category_id' => 3,
            'sku' => 'Playera-Bull-01',
            'name' => 'Playera de Bull',
            'slug' => 'playera-bull-01',
            'description' => 'Una playera con el logo de Bull.',
            'price' => 100.00,
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
            'limit_per_user' => 1, // <--- Limitamos a 1 por usuario
        ]);

        $trio->products()->attach([$hoodie->id, $collar->id, $gorra->id]); 
        
        Descomentar para dev*/

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
                'options' => ['Que vaya cómodo y con estilo — gorras que le queden bien y ropa streetwear.',
                    'Pasear con más seguridad y control — arneses, pecheras y correas resistentes.',
                    'Llevar todo sin complicarte — agua, termo, bolsas para desechos y mochila.'],
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
