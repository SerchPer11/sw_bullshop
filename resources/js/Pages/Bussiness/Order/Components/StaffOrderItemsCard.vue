<script setup>
import { Receipt } from 'lucide-vue-next';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';

defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    itemName: {
        type: Function,
        required: true,
    },
    itemType: {
        type: Function,
        required: true,
    },
    itemImage: {
        type: Function,
        required: true,
    },
    packageProducts: {
        type: Function,
        required: true,
    },
    packageProductImage: {
        type: Function,
        required: true,
    },
    selectedVariantDetails: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <NeoCard bg="white" padding="p-6">
        <FormTitles title="Artículos" subtitle="Contenido de la orden" :icon="Receipt" />

        <div class="mt-6 flex flex-col gap-4">
            <div
                v-for="item in items"
                :key="item.id"
                class="flex flex-col gap-4 border-2 border-bull-blue bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] sm:flex-row"
            >
                <!-- Imagen principal del Producto o Paquete -->
                <img
                    :src="itemImage(item)"
                    :alt="itemName(item)"
                    class="h-24 w-24 shrink-0 border-2 border-bull-blue bg-bull-cream object-cover"
                    loading="lazy"
                />

                <div class="flex flex-1 flex-col gap-2">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">
                        <div>
                            <p class="text-xs font-black uppercase text-bull-pink">
                                {{ itemType(item) }}
                            </p>
                            <h2 class="text-lg font-black text-bull-blue">
                                {{ itemName(item) }}
                            </h2>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="font-black text-bull-blue">${{ item.unit_price }} c/u</p>
                            <p
                                v-if="item.quantity > 1"
                                class="text-xs font-bold uppercase text-bull-pink"
                            >
                                Subtotal: ${{ (Number(item.unit_price || 0) * Number(item.quantity || 1)).toFixed(2) }}
                            </p>
                        </div>
                    </div>

                    <p class="text-sm font-bold text-bull-blue/70">
                        Cantidad: {{ item.quantity }}
                    </p>

                    <!-- Variante seleccionada (Producto individual) -->
                    <div
                        v-if="item.products"
                        class="border-t border-bull-blue/10 pt-3 text-sm text-bull-blue/70"
                    >
                        <span class="font-bold text-bull-blue">Selección:</span>
                        {{ selectedVariantDetails(item).name }}
                        <span
                            v-if="selectedVariantDetails(item).attributes.length"
                            class="text-bull-blue/60"
                        >
                            ({{ selectedVariantDetails(item).attributes.join(', ') }})
                        </span>
                    </div>

                    <!-- Desglose de productos incluidos (Paquete / Bundle) -->
                    <div
                        v-if="packageProducts(item).length"
                        class="flex flex-col gap-3 border-t border-bull-blue/10 pt-3"
                    >
                        <p class="text-xs font-black uppercase text-bull-blue/60">
                            Incluye en el paquete
                        </p>
                        <div
                            v-for="packageProduct in packageProducts(item)"
                            :key="packageProduct.id"
                            class="flex items-center gap-3 border border-bull-blue/20 bg-bull-cream/40 p-2 text-sm text-bull-blue/70"
                        >
                            <img
                                :src="packageProductImage(packageProduct)"
                                :alt="packageProduct.name"
                                class="h-12 w-12 shrink-0 border border-bull-blue bg-white object-cover"
                                loading="lazy"
                            />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-bold text-bull-blue">
                                    {{ packageProduct.name }}
                                </p>
                                <p class="text-xs sm:text-sm">
                                    <span class="font-bold text-bull-blue">Selección:</span>
                                    {{ selectedVariantDetails(item, packageProduct.id).name }}
                                    <span
                                        v-if="selectedVariantDetails(item, packageProduct.id).attributes.length"
                                        class="text-bull-blue/60"
                                    >
                                        ({{ selectedVariantDetails(item, packageProduct.id).attributes.join(', ') }})
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NeoCard>
</template>
