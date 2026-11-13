<template>
    <NeoCard  bg="white" padding="p-6" variant="static" class="flex flex-col gap-6">
    <!--div
        class="bg-bull-cream border-4 border-bull-blue p-6 shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] flex flex-col gap-6"-->
        <div class="flex flex-col gap-2">
            <h3 class="font-black text-xl uppercase text-bull-blue">Elige una opción</h3>
            <p class="text-sm font-medium text-bull-blue/80 leading-relaxed text-justify">
                Selecciona la variante del producto que deseas agregar a tu bolsa.
                Recuerda que algunos productos pueden tener un límite de unidades por persona.
            </p>
        </div>

        <!-- Iteramos sobre los productos que incluye este paquete -->
        <div class="flex flex-col gap-2">
            <span class="font-bold text-bull-blue text-sm uppercase">
                Elige: {{ product?.name }}
            </span>

            <!-- Botones de las variantes de este producto -->
            <div class="flex flex-wrap gap-2">
                <Button v-for="variant in product.variants" :key="variant.id"
                    @click="emit('select-variant', variant.id)" :disabled="variant.stock === 0" :class="[
                        'px-4 py-2 border-2 text-sm font-bold transition-all',
                        selectedVariant === variant.id
                            ? 'bg-bull-pink border-bull-blue text-white shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:bg-bull-pink'
                            : 'bg-white border-bull-blue text-bull-blue hover:bg-bull-pink hover:text-white',
                        variant.stock === 0 ? 'opacity-50 cursor-not-allowed grayscale' : ''
                    ]">
                    {{ variant.name }} <span v-if="variant.stock === 0">(Agotado)</span>
                </Button>
            </div>
        </div>
    </NeoCard>
</template>

<script setup>
import NeoCard from '@/components/Common/NeoCard.vue'
import { Button } from '@/components/ui/button';

defineProps({
    product: {
        type: Object,
        required: true
    },
    selectedVariant: {
        type: [Number, String],
        default: null
    }
});
const emit = defineEmits(['select-variant']);
</script>