<script setup>
import NeoCard from '@/components/Common/NeoCard.vue';
import NeoInput from '@/components/Common/NeoInput.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { Badge } from '@/components/ui/badge';
import {
    Boxes,
    ChevronLeft,
    ChevronRight,
    Package,
    Plus,
    Search,
    ShoppingBag,
    Store,
} from 'lucide-vue-next';

defineProps({
    activeTab: {
        type: String,
        required: true,
    },
    searchQuery: {
        type: String,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    productsCount: {
        type: Number,
        required: true,
    },
    packagesCount: {
        type: Number,
        required: true,
    },
    currentPage: {
        type: Number,
        required: true,
    },
    totalPages: {
        type: Number,
        required: true,
    },
    getVariants: {
        type: Function,
        required: true,
    },
    getPackageProducts: {
        type: Function,
        required: true,
    },
    getItemImage: {
        type: Function,
        required: true,
    },
    getCartQuantity: {
        type: Function,
        required: true,
    },
    availableStock: {
        type: Function,
        required: true,
    },
    getSelectedPackageVariant: {
        type: Function,
        required: true,
    },
    isPackageReady: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits([
    'update:activeTab',
    'update:searchQuery',
    'add-variant',
    'select-package-variant',
    'add-package',
    'change-page',
]);
</script>

<template>
    <div class="flex flex-col gap-6">
        <!-- Buscador y Tabs de Categoría (Productos vs Paquetes) -->
        <NeoCard bg="white-neon" padding="p-5">
            <FormTitles
                title="Catálogo Físico"
                subtitle="Selecciona productos individuales o arma paquetes en tiempo real"
                :icon="Store"
            />

            <!-- Tabs Brutalistas -->
            <div class="grid grid-cols-2 gap-3 mt-5">
                <button
                    type="button"
                    @click="emit('update:activeTab', 'products')"
                    class="flex items-center justify-center gap-2 py-3 px-4 font-black uppercase text-xs sm:text-sm tracking-wider border-4 border-bull-blue transition-all"
                    :class="
                        activeTab === 'products'
                            ? 'bg-bull-blue text-bull-neon shadow-[4px_4px_0px_0px_rgba(242,17,109,1)]'
                            : 'bg-white text-bull-blue hover:bg-bull-cream'
                    "
                >
                    <ShoppingBag class="h-4 w-4 shrink-0" />
                    <span>Productos ({{ productsCount }})</span>
                </button>

                <button
                    type="button"
                    @click="emit('update:activeTab', 'packages')"
                    class="flex items-center justify-center gap-2 py-3 px-4 font-black uppercase text-xs sm:text-sm tracking-wider border-4 border-bull-blue transition-all"
                    :class="
                        activeTab === 'packages'
                            ? 'bg-bull-blue text-bull-neon shadow-[4px_4px_0px_0px_rgba(242,17,109,1)]'
                            : 'bg-white text-bull-blue hover:bg-bull-cream'
                    "
                >
                    <Boxes class="h-4 w-4 shrink-0" />
                    <span>Paquetes ({{ packagesCount }})</span>
                </button>
            </div>

            <div class="mt-4">
                <NeoInput
                    id="pos-search"
                    :model-value="searchQuery"
                    :placeholder="
                        activeTab === 'products'
                            ? 'Buscar prenda individual, talla o SKU...'
                            : 'Buscar paquete o prenda incluida...'
                    "
                    @update:model-value="emit('update:searchQuery', $event)"
                />
            </div>
        </NeoCard>

        <!-- Estado vacío -->
        <NeoCard v-if="!items.length" bg="white" padding="p-8" class="text-center">
            <Search class="mx-auto h-10 w-10 text-bull-pink" />
            <p class="mt-3 text-lg font-black uppercase text-bull-blue">
                Sin resultados en {{ activeTab === 'products' ? 'Productos' : 'Paquetes' }}
            </p>
            <p class="text-sm font-medium text-bull-blue/70">
                Intenta con otro término de búsqueda o cambia de pestaña.
            </p>
        </NeoCard>

        <!-- TAB 1: GRID DE PRODUCTOS INDIVIDUALES -->
        <div v-else-if="activeTab === 'products'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <NeoCard
                v-for="product in items"
                :key="product.id"
                bg="white"
                padding="p-0"
                class="flex flex-col overflow-hidden"
            >
                <div class="flex items-center gap-4 border-b-4 border-bull-blue bg-bull-blue p-4 text-bull-cream">
                    <img
                        :src="getItemImage(product)"
                        :alt="product.name"
                        class="h-16 w-16 shrink-0 border-2 border-bull-neon bg-white object-cover"
                    />
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-black uppercase tracking-widest text-bull-neon">
                            Producto Individual
                        </p>
                        <h3 class="truncate text-lg font-black uppercase tracking-tight">
                            {{ product.name }}
                        </h3>
                    </div>
                </div>

                <div class="flex flex-col gap-2.5 p-4 flex-1 justify-between">
                    <div class="grid grid-cols-1 gap-2.5">
                        <button
                            v-for="variant in getVariants(product)"
                            :key="variant.id"
                            type="button"
                            :disabled="availableStock(variant) <= 0"
                            @click="emit('add-variant', product, variant)"
                            class="group flex items-center justify-between gap-3 border-2 border-bull-blue p-3 text-left transition-all duration-150"
                            :class="
                                availableStock(variant) > 0
                                    ? 'bg-white hover:bg-bull-neon shadow-[4px_4px_0px_0px_rgba(5,76,104,1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none cursor-pointer'
                                    : 'bg-slate-100 opacity-50 cursor-not-allowed border-dashed'
                            "
                        >
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-black uppercase text-sm text-bull-blue">
                                        {{ variant.name }}
                                    </span>
                                    <Badge
                                        v-if="getCartQuantity(variant.id) > 0"
                                        variant="neo-pink"
                                        shape="square"
                                        class="text-[10px]"
                                    >
                                        En ticket: {{ getCartQuantity(variant.id) }}
                                    </Badge>
                                </div>
                                <p class="text-xs font-bold text-bull-blue/70 mt-0.5">
                                    <span v-if="availableStock(variant) > 0">
                                        Disponibles: {{ availableStock(variant) }}
                                    </span>
                                    <span v-else class="text-red-600 uppercase font-black">
                                        Agotado (Stock: 0)
                                    </span>
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-base font-black text-bull-blue">
                                    ${{ Number(variant.price).toFixed(2) }}
                                </span>
                                <span
                                    class="flex h-8 w-8 items-center justify-center border-2 border-bull-blue transition-colors"
                                    :class="
                                        availableStock(variant) > 0
                                            ? 'bg-bull-pink text-white group-hover:bg-bull-blue group-hover:text-bull-neon'
                                            : 'bg-slate-300 text-slate-500'
                                    "
                                >
                                    <Plus class="h-4 w-4 stroke-[3]" />
                                </span>
                            </div>
                        </button>
                    </div>

                    <p
                        v-if="!getVariants(product).length"
                        class="flex items-center gap-2 text-xs font-bold uppercase text-bull-blue/60 py-2"
                    >
                        <Package class="h-4 w-4" /> Sin variantes activas
                    </p>
                </div>
            </NeoCard>
        </div>

        <!-- TAB 2: GRID DE PAQUETES / BUNDLES -->
        <div v-else class="grid grid-cols-1 gap-6">
            <NeoCard
                v-for="pkg in items"
                :key="pkg.id"
                bg="white"
                padding="p-0"
                class="flex flex-col overflow-hidden"
            >
                <!-- Cabecera del Paquete -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-4 border-bull-pink bg-bull-blue p-4 text-bull-cream">
                    <div class="flex items-center gap-4">
                        <img
                            :src="getItemImage(pkg)"
                            :alt="pkg.name"
                            class="h-16 w-16 shrink-0 border-2 border-bull-neon bg-white object-cover"
                        />
                        <div>
                            <Badge variant="neo-neon" shape="square" class="text-[10px]">
                                Paquete / Bundle
                            </Badge>
                            <h3 class="mt-1 text-xl font-black uppercase tracking-tight">
                                {{ pkg.name }}
                            </h3>
                        </div>
                    </div>

                    <div class="text-left sm:text-right shrink-0">
                        <p class="text-xs font-bold uppercase text-bull-cream/70">Precio Paquete</p>
                        <p class="text-2xl font-black text-bull-neon">
                            ${{ Number(pkg.price ?? pkg.display_price ?? 0).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <!-- Selección de Variantes por cada Producto del Paquete -->
                <div class="p-5 flex flex-col gap-5">
                    <div
                        v-for="pkgProduct in getPackageProducts(pkg)"
                        :key="pkgProduct.id"
                        class="border-2 border-bull-blue/20 p-3.5 bg-bull-cream/20"
                    >
                        <div class="flex items-center justify-between gap-2 mb-2.5">
                            <p class="text-xs font-black uppercase tracking-wide text-bull-blue">
                                {{ pkgProduct.name }}
                            </p>
                            <span
                                v-if="getSelectedPackageVariant(pkg.id, pkgProduct.id)"
                                class="text-xs font-black uppercase text-bull-pink"
                            >
                                Seleccionado: {{ getSelectedPackageVariant(pkg.id, pkgProduct.id).name }}
                            </span>
                            <span v-else class="text-[11px] font-bold uppercase text-bull-blue/50">
                                Elige una talla/variante
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="variant in getVariants(pkgProduct)"
                                :key="variant.id"
                                type="button"
                                :disabled="availableStock(variant) <= 0"
                                @click="emit('select-package-variant', pkg.id, pkgProduct.id, variant)"
                                class="px-3 py-2 text-xs font-black uppercase border-2 transition-all"
                                :class="
                                    availableStock(variant) <= 0
                                        ? 'border-slate-300 bg-slate-100 text-slate-400 cursor-not-allowed line-through'
                                        : getSelectedPackageVariant(pkg.id, pkgProduct.id)?.id === variant.id
                                            ? 'border-bull-blue bg-bull-blue text-bull-neon shadow-[3px_3px_0px_0px_rgba(242,17,109,1)]'
                                            : 'border-bull-blue bg-white text-bull-blue hover:bg-bull-neon'
                                "
                            >
                                {{ variant.name }}
                                <span class="ml-1 text-[10px] opacity-80">
                                    ({{ availableStock(variant) }})
                                </span>
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        :disabled="!isPackageReady(pkg)"
                        @click="emit('add-package', pkg)"
                        class="w-full py-3.5 px-4 flex items-center justify-center gap-2 font-black uppercase text-sm tracking-wider border-3 border-bull-blue transition-all"
                        :class="
                            isPackageReady(pkg)
                                ? 'bg-bull-pink text-white hover:bg-bull-neon hover:text-bull-blue shadow-[4px_4px_0px_0px_rgba(5,76,104,1)] cursor-pointer'
                                : 'bg-slate-200 text-slate-400 border-slate-300 cursor-not-allowed'
                        "
                    >
                        <Plus class="h-4 w-4 stroke-[3]" />
                        <span>
                            {{
                                isPackageReady(pkg)
                                    ? 'Agregar Paquete al Ticket'
                                    : 'Selecciona las tallas del paquete para agregar'
                            }}
                        </span>
                    </button>
                </div>
            </NeoCard>
        </div>

        <!-- PAGINACIÓN CLIENT-SIDE (No recarga ni pierde el ticket) -->
        <div
            v-if="totalPages > 1"
            class="flex items-center justify-between border-4 border-bull-blue bg-white px-4 py-3 shadow-[4px_4px_0px_0px_rgba(5,76,104,1)]"
        >
            <button
                type="button"
                :disabled="currentPage <= 1"
                @click="emit('change-page', currentPage - 1)"
                class="flex items-center gap-1 px-3 py-1.5 text-xs font-black uppercase border-2 border-bull-blue bg-bull-cream hover:bg-bull-neon text-bull-blue disabled:opacity-40 disabled:cursor-not-allowed"
            >
                <ChevronLeft class="h-4 w-4" /> Anterior
            </button>

            <span class="text-xs font-black uppercase text-bull-blue">
                Página {{ currentPage }} de {{ totalPages }}
            </span>

            <button
                type="button"
                :disabled="currentPage >= totalPages"
                @click="emit('change-page', currentPage + 1)"
                class="flex items-center gap-1 px-3 py-1.5 text-xs font-black uppercase border-2 border-bull-blue bg-bull-cream hover:bg-bull-neon text-bull-blue disabled:opacity-40 disabled:cursor-not-allowed"
            >
                Siguiente <ChevronRight class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>
