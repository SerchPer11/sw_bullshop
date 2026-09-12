<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import ProductCard from '@/components/Lobby/ProductCard.vue'; 

const props = defineProps({
    title1: {
        type: String,
        required: true
    },
    title2: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
    packagesData: {
        type: Object,
        required: true
    },
    productsData: {
        type: Object,
        required: true
    }
});

const packs = props.packagesData.data;
const prods = props.productsData.data;
</script>

<template>
    <Head title="Colección | BullShop" />

    <MainLayout>
        <!-- CABECERA DE LA COLECCIÓN -->
        <section class="bg-bull-blue text-bull-cream py-12 px-4 border-b-4 border-bull-blue">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter">
                        {{ title1 }} <span class="text-bull-neon"> {{ title2 }}</span>
                    </h1>
                    <p class="mt-4 text-bull-cream/80 font-medium max-w-xl">
                        {{ description }}
                    </p>
                </div>
                
                <!-- Contador de resultados actualizado -->
                <div class="bg-bull-neon text-bull-blue font-bold uppercase text-xs px-4 py-2 border-2 border-bull-cream shadow-[4px_4px_0px_0px_rgba(253,231,221,1)]">
                    Mostrando {{ packs.length + prods.length }} piezas
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
                    v-for="pkg in packs" 
                    :key="pkg.id" 
                    :product="pkg" 
                    route="/paquete/"
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
                    v-for="product in prods" 
                    :key="product.id" 
                    :product="product" 
                />
            </div>
        </section>
        
    </MainLayout>
</template>