<script setup>
import { ref } from 'vue';
import { useCartStore } from '@/./Stores/useCartStore';
import { ShoppingBag } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
    SheetTrigger,
} from '@/components/ui/sheet';
import ProductList from '@/components/Lobby/ShoppingBag/ProductList.vue';

const cartStore = useCartStore();
const isOpen = ref(false);

const props = defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'neo-neon'
    },
    size: {
        type: String,
        default: 'default'
    },
    shape: {
        type: String,
        default: 'pill'
    },
    showable: {
        type: Boolean,
        default: true
    },
    label: {
        type: String,
        default: 'Bolsa'
    },
    showCount: {
        type: Boolean,
        default: true
    },
    class: {
        type: [String, Object, Array],
        default: ''
    }
});

const emit = defineEmits(['open-change']);

const handleOpenChange = (value) => {
    isOpen.value = value;
    emit('open-change', value);
};
</script>

<template>
    <Sheet :open="isOpen" @update:open="handleOpenChange">
        <!-- EL GATILLO: Por defecto mantiene el estilo del Navbar, pero acepta props para personalizarse -->
        <SheetTrigger as-child>
            <Button
                v-if="!props.isMobileMenuOpen"
                :variant="props.variant"
                :size="props.size"
                :shape="props.shape"
                :showable="props.showable"
                :icon="ShoppingBag"
                :persistent="props.showable && props.showCount && cartStore.totalItems > 0 ? `(${cartStore.totalItems})` : null"
                :class="props.class"
                title="Ver productos en la bolsa"
            >
                {{ props.label }}
                <span v-if="!props.showable && props.showCount && cartStore.totalItems > 0" class="font-black">
                    ({{ cartStore.totalItems }})
                </span>
            </Button>
            <Button
                v-else
                :variant="props.variant"
                :size="props.size"
                :shape="props.shape"
                :class="props.class || 'w-full'"
                title="Ver productos en la bolsa"
            >
                <ShoppingBag class="w-5 h-5" />
                {{ props.label }}
                <span v-if="props.showCount && cartStore.totalItems > 0" class="text-bull-pink/80 font-bold">
                    ({{ cartStore.totalItems }})
                </span>
            </Button>
        </SheetTrigger>

        <!-- EL CONTENIDO DEL SLIDE-OVER -->
        <SheetContent class="w-full sm:max-w-md bg-bull-cream border-l-2 border-bull-blue md:shadow-[0px_0px_4px_4px_rgba(0,39,49,1)] flex flex-col p-0">
            <SheetHeader class="p-6 border-b-4 border-bull-pink bg-bull-blue">
                <SheetTitle class="font-black text-2xl uppercase text-bull-cream flex items-center gap-2">
                    <ShoppingBag class="w-6 h-6" /> Tu Bolsa
                </SheetTitle>
                <SheetDescription class="text-bull-cream/90 text-sm mt-1 text-justify">
                    Revisa los productos que has apartado y cuando estés listo, procede a la compra.
                </SheetDescription>
            </SheetHeader>

            <ProductList :cartStore="cartStore" :isMobile="props.isMobileMenuOpen" />


            <!-- FOOTER CON TOTAL Y CHECKOUT -->
            <div v-if="cartStore.items.length > 0"
                class="border-t-4 border-bull-pink p-6 bg-bull-blue flex flex-col gap-4">
                <div class="flex justify-between items-center font-black text-xl text-white uppercase">
                    <span>Total</span>
                    <span>${{ cartStore.totalAmount }} MXN</span>
                </div>
                
                <Button variant="neo-neon" size="xl" class="w-full text-lg uppercase tracking-wider"
                    routeName="checkout.index" title="Proceder al pago">
                    Proceder a pagar
                </Button>
            </div>
        </SheetContent>
    </Sheet>
</template>