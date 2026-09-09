<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import ProductCard from '@/components/Lobby/ProductCard.vue'; 
import { ShoppingCart } from 'lucide-vue-next';

// 📦 Datos falsos: PAQUETES DESTACADOS (3 elementos)
const packages = [
    { id: 101, name: 'Paquete Alpha (Arnés + Correa)', price: 3200, image: '/Images/Lobby/img_hero1.webp', isNew: true, stock: 5 },
    { id: 102, name: 'Set Street Duetto Completo', price: 4500, isFeatured: true, originalPrice: 5000, image: '/Images/Lobby/img_hero2.webp', isNew: false, stock: 2 },
    { id: 103, name: 'Combo Invierno (Hoodie + Bandana)', price: 1800, compare_price: 2200, image: '/Images/Lobby/img_hero1.webp', isNew: true, stock: 10 },
];

// 📦 Datos falsos: PIEZAS INDIVIDUALES
const products = [
    { id: 1, name: 'Set Street Duetto', price: 3850, image: '/Images/Lobby/img_hero2.webp', isNew: true, stock: 15 },
    { id: 2, name: 'Arnés Táctico Titan', price: 2100, image: '/Images/Lobby/img_hero1.webp', isNew: false, stock: 3 },
    { id: 3, name: 'Hoodie Oversize "Barrel"', price: 1500, image: '/Images/Lobby/img_hero2.webp', isNew: true, stock: 20 },
    { id: 4, name: 'Collar Eslabón de Acero', price: 950, image: '/Images/Lobby/img_hero1.webp', isNew: false, stock: 0 },
    { id: 5, name: 'Bandana Neón Flash', price: 450, image: '/Images/Lobby/img_hero2.webp', isNew: false, stock: 12 },
    { id: 6, name: 'Rompevientos Ciudad', price: 2800, image: '/Images/Lobby/img_hero1.webp', isNew: true, stock: 4 },
];
</script>

<template>
    <Head title="Colección | BullShop" />

    <MainLayout>
        <!-- CABECERA DE LA COLECCIÓN -->
        <section class="bg-bull-blue text-bull-cream py-12 px-4 border-b-4 border-bull-blue">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter">
                        Drop <span class="text-bull-neon">Actual</span>
                    </h1>
                    <p class="mt-4 text-bull-cream/80 font-medium max-w-xl">
                        Equipamiento táctico y streetwear diseñado específicamente para la anatomía de tu bulldog. Ediciones limitadas.
                    </p>
                </div>
                
                <!-- Contador de resultados actualizado -->
                <div class="bg-bull-neon text-bull-blue font-bold uppercase text-xs px-4 py-2 border-2 border-bull-cream shadow-[4px_4px_0px_0px_rgba(253,231,221,1)]">
                    Mostrando {{ packages.length + products.length }} piezas
                </div>
            </div>
        </section>

        <!-- SECCIÓN 1: PAQUETES ACTIVOS -->
        <section class="max-w-7xl mx-auto px-4 pt-12 md:pt-20">
            <!-- Título de sección -->
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-2xl md:text-3xl font-black text-bull-blue uppercase tracking-wide">
                    Paquetes Destacados
                </h2>
                <!-- Línea decorativa -->
                <div class="h-1 flex-grow bg-bull-blue/10"></div>
            </div>

            <!-- Grid de Paquetes (Máximo 3 columnas para que destaquen más) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <ProductCard 
                    v-for="pkg in packages" 
                    :key="pkg.id" 
                    :product="pkg" 
                />
            </div>
        </section>

        <!-- SECCIÓN 2: GRID DE PIEZAS INDIVIDUALES -->
        <section class="max-w-7xl mx-auto px-4 py-12 md:py-20">
            <!-- Título de sección -->
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-2xl md:text-3xl font-black text-bull-blue uppercase tracking-wide">
                     Piezas Individuales
                </h2>
                <div class="h-1 flex-grow bg-bull-blue/10"></div>
            </div>

            <!-- Grid de Productos (Llega hasta 4 columnas en pantallas muy grandes) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                <ProductCard 
                    v-for="product in products" 
                    :key="product.id" 
                    :product="product" 
                />
            </div>
        </section>
        
    </MainLayout>
</template>