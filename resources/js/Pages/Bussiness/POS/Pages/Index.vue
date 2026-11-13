<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import POSCatalog from '../Components/POSCatalog.vue';
import POSTicket from '../Components/POSTicket.vue';
import { usePOS } from '../Composables/usePOS';

const props = defineProps({
    title: {
        type: String,
        default: 'Punto de Venta (POS)',
    },
    productsData: {
        type: Object,
        required: true,
    },
    packagesData: {
        type: Object,
        default: () => ({ data: [] }),
    },
});

const {
    activeTab,
    searchQuery,
    currentPage,
    totalPages,
    paginatedItems,
    productsCount,
    packagesCount,
    cartItems,
    form,
    getVariants,
    getPackageProducts,
    getItemImage,
    getCartQuantity,
    availableStock,
    addVariantToCart,
    selectPackageVariant,
    getSelectedPackageVariant,
    isPackageReady,
    addPackageToCart,
    canIncrementItem,
    incrementQuantity,
    decrementQuantity,
    removeItem,
    clearCart,
    goToPage,
    totalItemsCount,
    totalAmount,
    canCheckout,
    submitSale,
} = usePOS(props);
</script>

<template>
    <MainLayout>
        <Head :title="`${title} | BullShop`" />

        <!-- CABECERA BRUTALISTA DEL POS -->
        <section class="bg-bull-blue text-bull-cream py-8 px-4 border-b-4 border-bull-pink">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-bull-neon">
                        BullShop · Operación en Evento
                    </p>
                    <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tighter">
                        Punto de <span class="text-bull-neon">Venta Pop-Up</span>
                    </h1>
                </div>

                <div class="bg-bull-pink text-white font-black uppercase text-xs px-4 py-2 border-2 border-bull-cream shadow-[4px_4px_0px_0px_rgba(212,232,24,1)]">
                    Inventario en Tiempo Real
                </div>
            </div>
        </section>

        <!-- PANTALLA DIVIDIDA: CATÁLOGO (IZQUIERDA) + TICKET/CARRITO (DERECHA) -->
        <section class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Columna Izquierda: Catálogo de Productos y Paquetes (7 columnas) -->
                <div class="lg:col-span-7">
                    <POSCatalog
                        v-model:active-tab="activeTab"
                        v-model:search-query="searchQuery"
                        :items="paginatedItems"
                        :products-count="productsCount"
                        :packages-count="packagesCount"
                        :current-page="currentPage"
                        :total-pages="totalPages"
                        :get-variants="getVariants"
                        :get-package-products="getPackageProducts"
                        :get-item-image="getItemImage"
                        :get-cart-quantity="getCartQuantity"
                        :available-stock="availableStock"
                        :get-selected-package-variant="getSelectedPackageVariant"
                        :is-package-ready="isPackageReady"
                        @add-variant="addVariantToCart"
                        @select-package-variant="selectPackageVariant"
                        @add-package="addPackageToCart"
                        @change-page="goToPage"
                    />
                </div>

                <!-- Columna Derecha: Ticket / Carrito (5 columnas) -->
                <div class="lg:col-span-5">
                    <POSTicket
                        :cart-items="cartItems"
                        :total-items-count="totalItemsCount"
                        :total-amount="totalAmount"
                        :form="form"
                        :can-checkout="canCheckout"
                        :can-increment-item="canIncrementItem"
                        @increment="incrementQuantity"
                        @decrement="decrementQuantity"
                        @remove="removeItem"
                        @clear="clearCart"
                        @submit="submitSale"
                    />
                </div>
            </div>
        </section>
    </MainLayout>
</template>
