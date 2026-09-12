<template>
    <Head :title="`${pack.name} | BullShop`" />

    <MainLayout>
        <section class="max-w-7xl mx-auto px-4 py-12 md:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                
                <!-- COLUMNA IZQ: Galería / Imagen Principal -->
                <div class="bg-bull-cream border-4 border-bull-blue shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] p-4 relative">
                    <Badge v-if="pack.is_featured" variant="neo-pink" shape="pill" class="absolute top-6 left-6 z-10">
                        ⚡ Drop Destacado
                    </Badge>
                    <Badge v-else-if="pack.is_new" variant="neo-neon" shape="pill" class="absolute top-4 left-4 z-10">
                        Nuevo
                    </Badge>
                    <!-- Aquí irá la imagen dinámica -->
                    <img src="/Images/Lobby/img_hero1.webp" :alt="pack.name" class="w-full h-auto object-cover border-2 border-bull-blue/20" />
                </div>

                <!-- COLUMNA DER: Info y Compra -->
                <div class="flex flex-col space-y-8">
                    
                    
                    <div>
                        <h1 class="text-4xl sm:text-5xl font-black uppercase text-bull-blue leading-tight tracking-tighter">
                            {{ pack.name }}
                        </h1>
                        <div class="flex items-end gap-4 mt-4">
                            <span class="text-4xl font-black text-bull-pink">${{ pack.price }} MXN</span>
                            <span v-if="pack.compare_at_price" class="text-xl font-bold text-bull-blue/50 line-through mb-1">
                                ${{ pack.compare_at_price }}
                            </span>
                        </div>
                    </div>

                    <p class="text-lg font-medium text-bull-blue/80 leading-relaxed">
                        {{ pack.description }}
                    </p>

                    <!-- Desglose de lo que incluye el paquete -->
                    <ProductsCard :items="pack.products" />

                    

                    <!-- Acción Principal -->
                    <div class="pt-4">
                        <Button variant="neo-pink" size="xl" class="w-full text-xl flex items-center justify-center gap-3" :disabled="pack.stock <= 0">
                            <ShoppingBag class="w-6 h-6" />
                            {{ pack.stock > 0 ? 'Apartar Set Completo' : 'Agotado' }}
                        </Button>
                        <p class="text-center text-xs font-bold uppercase text-bull-blue/60 mt-4">
                            Quedan solo {{ pack.stock }} paquetes disponibles
                        </p>
                    </div>
                    
                </div>
                
            </div>
            <!-- Productos Recomendados -->
                    <CarrouselSugestions :items="recommendedProducts" />
        </section>
    </MainLayout>
</template>

<script setup>
import CarrouselSugestions from '@/Pages/Bussines/Packages/Components/CarrouselSugestions.vue';
import ProductsCard from '@/Pages/Bussines/Packages/Components/ProductsCard.vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingBag } from 'lucide-vue-next';



const props = defineProps({
    title: {
        type: String,
        required: true
    },
    packageData: {
        type: Object,
        required: true
    },
    recommendedProducts: {
        type: Object,
        required: true
    }
});

const pack = props.packageData.data;
</script>