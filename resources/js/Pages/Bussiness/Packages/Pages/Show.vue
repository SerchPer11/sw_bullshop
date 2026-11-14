<template>

    <Head :title="`${pack?.name} | BullShop`" />

    <MainLayout>
        <section class="max-w-7xl mx-auto px-4 py-12 md:py-20">
            <div class="flex items-center gap-4 mb-8">
                <Button variant="neo-blue-ghost" routeName="collection.index" shape="pill">
                    <Undo2 />
                    <span class="sm:hidden">Volver a colección</span>
                    <span class="hidden sm:block">Volver a colección</span>
                </Button>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                <div
                    class="bg-bull-cream border-4 border-bull-blue shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] p-4 relative my-auto">
                    <Badge v-if="pack?.is_featured" variant="neo-pink" shape="pill" class="absolute top-6 left-6 z-10">
                        ⚡ Drop Destacado
                    </Badge>

                    <Carousel :items="pack?.photos ?? pack?.photos[0]" :show-controls="pack?.photos.length > 1"
                        showQuantity :itemsToShow="Number(1)" gap="gap-4 md:gap-6 lg:gap-8"
                        class="[--carousel-gap:24px] lg:[--carousel-gap:32px]" autoplay>
                        <template #default="{ item: photo }">
                            <img :src="pack?.photos[0]?.url || '/placeholder.png'" :alt="pack?.name"
                                class="w-full h-auto object-containt border-2 border-bull-blue/20 aspect-responsive object-cover" />
                        </template>
                    </Carousel>
                </div>

                <!-- COLUMNA DER: Info y Compra -->
                <div class="flex flex-col space-y-4">

                    <div>
                        <h1
                            class="text-4xl sm:text-5xl font-black uppercase text-bull-blue leading-tight tracking-tighter">
                            {{ pack?.name }}
                        </h1>
                        <div class="flex items-end gap-4 mt-2">
                            <span v-if="pack?.compare_at_price"
                                class="text-xl font-bold text-bull-blue/50 line-through mb-1">
                                ${{ pack?.compare_at_price }}
                            </span>
                            <span class="text-4xl font-black text-bull-pink">${{ pack?.display_price }} MXN</span>
                        </div>
                    </div>

                    <p class="text-lg font-medium text-bull-blue/80 leading-relaxed text-justify">
                        {{ pack?.description }}
                    </p>

                    <ProductsSelecction :products="pack?.products" :selections="selections"
                        @select-variant="selectVariant" />

                    <div class="pt-4">
                        <p v-if="pack?.limit_per_user"
                            class="text-center text-md font-bold uppercase text-bull-pink mb-2">
                            Limitado a {{ pack?.limit_per_user }} por persona
                        </p>
                        <!-- El botón se habilita SOLO si isReadyToBuy es true -->
                        <Button variant="neo-neon" size="xl"
                            class="w-full text-xl flex items-center justify-center gap-3 transition-all"
                            :disabled="pack?.stock <= 0 || !isReadyToBuy || isDisabled" @click="handleAddToCart">
                            <ShoppingBag class="w-6 h-6" v-if="isReadyToBuy && !isDisabled" />
                            {{
                                pack?.stock === 0 ? 'Agotado' :
                                    (!isReadyToBuy ? 'Completa tu selección' : isDisabled ? 'Límite alcanzado' : 'Apartar Set Completo')
                            }}
                        </Button>
                        <p v-if="pack?.stock <= 10"
                            class="text-center text-xs font-bold uppercase text-bull-blue/60 mt-4">
                            Quedan solo {{ pack?.stock }} paquetes disponibles
                        </p>
                    </div>

                </div>
            </div>

            <div class="mt-20">
                <CarrouselSugestions :items="recommendedProducts" />
            </div>
        </section>

        <!-- Modal de Confirmación Post-Add-To-Cart -->
        <PostAddToCartModal
            :show="showCartModal"
            :item="addedItem"
            @close="showCartModal = false"
        />
    </MainLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import CarrouselSugestions from '@/Pages/Bussiness/Packages/Components/CarrouselSugestions.vue';
import PostAddToCartModal from '@/components/Lobby/ShoppingBag/PostAddToCartModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingBag, Undo2 } from 'lucide-vue-next';
import ProductsSelecction from '@/Pages/Bussiness/Packages/Components/ProductsSelecction.vue';
import { usePackages } from '@/Pages/Bussiness/Packages/Composables/usePackages';
import Carousel from '@/components/Common/Carousel.vue';

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

const {
    selectVariant,
    isReadyToBuy,
    handleAddToCart,
    selections,
    pack,
    isDisabled,
    showCartModal,
    addedItem,
} = usePackages(props);
</script>