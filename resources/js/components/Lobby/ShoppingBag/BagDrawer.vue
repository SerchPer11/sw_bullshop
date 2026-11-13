<script setup>
import { useCartStore } from '@/./Stores/useCartStore';
import { Link } from '@inertiajs/vue3';
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
import Separator from '@/components/Common/Separator.vue';
import { TicketPercent } from 'lucide-vue-next';

const cartStore = useCartStore();

const props = defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        required: false
    }
});
const isMobileMenuOpen = props.isMobileMenuOpen;

const calcIva = (amount) => {
    return amount * 0.16;
};

</script>

<template>
    <Sheet>
        <!-- EL GATILLO: Este es el ícono que irá en tu Navbar -->
        <SheetTrigger as-child>
            <Button v-if="!isMobileMenuOpen" variant="neo-neon" size="default" shape="pill"
                :showable="true" :icon="ShoppingBag"
                :persistent="cartStore.totalItems > 0 ? `(${cartStore.totalItems})` : null">
                Bolsa
            </Button>
            <Button v-else-if="isMobileMenuOpen" variant="neo-neon" class="w-full" shape="pill" >
                <ShoppingBag class="w-5 h-5" />
                Bolsa
                <span v-if="cartStore.totalItems > 0" class="text-bull-pink/80 font-bold">
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

            <ProductList :cartStore="cartStore" />

            <!-- FOOTER CON TOTAL Y CHECKOUT -->
            <div v-if="cartStore.items.length > 0"
                class="border-t-4 border-bull-pink p-6 bg-bull-blue flex flex-col gap-4">
                <div class="flex justify-between items-center font-black text-xl text-white uppercase">
                    <span>Total</span>
                    <span>${{ cartStore.totalAmount }} MXN</span>
                </div>
                
                <Button variant="neo-neon" size="xl" class="w-full text-lg uppercase tracking-wider"
                    routeName="checkout.index">
                    Proceder a pagar
                </Button>
            </div>
        </SheetContent>
    </Sheet>
</template>