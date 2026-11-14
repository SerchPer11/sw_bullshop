<template>

    <Head :title="`${product?.name} | BullShop`" />

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
                    <Badge v-if="product?.is_featured" variant="neo-pink" shape="pill" class="absolute top-6 left-6 z-10">
                        ⚡ Drop Destacado
                    </Badge>

                    <!-- Imagen:
                        Se agregara el carousel para mostrar la colección de imagenes -->
                    <img :src="product?.photos[0]?.url || '/placeholder.png'" :alt="product?.name"
                        class="w-full h-auto object-containt border-2 border-bull-blue/20 aspect-square" />
                </div>

                <!-- COLUMNA DER: Info y Compra -->
                <div class="flex flex-col space-y-4">

                    <div>
                        <h1
                            class="text-4xl sm:text-5xl font-black uppercase text-bull-blue leading-tight tracking-tighter">
                            {{ product?.name }}
                        </h1>
                        <div class="flex items-end gap-4 mt-2">
                            <span v-if="product?.compare_at_price"
                                class="text-xl font-bold text-bull-blue/50 line-through mb-1">
                                ${{ product?.compare_at_price }}
                            </span>
                            <span class="text-4xl font-black text-bull-pink ">
                                ${{ product?.display_price }} MXN
                            </span>
                            
                        </div>
                    </div>

                    <p class="text-lg font-medium text-bull-blue/80 leading-relaxed text-justify">
                        {{ product?.description }}
                    </p>

                    <div class="flex flex-col ml-2">
                        <span class="text-md text-bull-blue font-semibold">Cantidad:</span>

                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <Button
                                    @click="decrementProductCount"
                                    variant="neo-pink" size="icon" shape="rounded" class="h-8 w-8">
                                    <Minus class="w-8 h-8" />
                                </Button>
                                <span class="text-lg font-bold text-bull-blue bg-bull-blue/10 px-2 py-1 rounded">
                                    {{ productCount }}
                                </span>
                                <Button 
                                    @click="incrementProductCount"
                                    variant="neo-neon" size="icon" shape="rounded" class="h-8 w-8">
                                    <Plus class="w-8 h-8" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <ProductsSelecction
                        :product="product"
                        :selected-variant="selectedVariant"
                        @select-variant="selectVariant"
                    />

                    <div class="pt-4">
                        <p v-if="product?.limit_per_user" class="text-center text-md font-bold uppercase text-bull-pink mb-2">
                            Limitado a {{ product?.limit_per_user }} por persona
                        </p>
                        <!-- El botón se habilita SOLO si isReadyToBuy es true -->
                        <Button variant="neo-neon" size="xl"
                            class="w-full text-xl flex items-center justify-center gap-3 transition-all"
                            :disabled="product?.stock <= 0 || !isReadyToBuy || isDisabled" @click="handleAddToCart">
                            <ShoppingBag class="w-6 h-6" v-if="isReadyToBuy" />
                            {{
                                product?.stock === 0 ? 'Agotado' :
                                    (!isReadyToBuy ? 'Completa tu selección' : 'Apartar Set Completo')
                            }}
                        </Button>
                        <p v-if="product?.stock < 10" class="text-center text-xs font-bold uppercase text-bull-blue/60 mt-4">
                            Quedan solo {{ product?.stock }} paquetes disponibles
                        </p>
                    </div>

                </div>
            </div>

            <div class="mt-20">
                <CarrouselSugestions :items="recommendedPackages" />
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
import CarrouselSugestions from '@/Pages/Bussiness/Products/Components/CarrouselSugestions.vue';
import ProductsSelecction from '@/Pages/Bussiness/Products/Components/ProductsSelecction.vue';
import PostAddToCartModal from '@/components/Lobby/ShoppingBag/PostAddToCartModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingBag, Minus, Plus, Undo2 } from 'lucide-vue-next';
import { useProducts } from '@/Pages/Bussiness/Products/Composables/useProducts';
import Carousel from '@/components/Common/Carousel.vue';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    productData: {
        type: Object,
        required: true
    },
    recommendedPackages: {
        type: Object,
        required: true
    }
});

const {
    selectVariant,
    isReadyToBuy,
    handleAddToCart,
    product,
    selectedVariant,
    isDisabled,
    productCount,
    incrementProductCount,
    decrementProductCount,
    showCartModal,
    addedItem,
} = useProducts(props);
</script>