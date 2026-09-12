<template>
    <div class="bg-bull-blue text-bull-cream p-6 border-4 border-bull-blue">
        <h3 class="font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
            <ListChecks class="text-bull-neon w-5 h-5" />
            Este set incluye:
        </h3>
        <div v-for="item in items" :key="item.id"
            class="grid grid-cols-3 items-center text-center border-b border-bull-cream/20 py-2">
            <span class="font-medium justify-self-start">{{ item.name }}</span>
            <span class="text-sm text-bull-cream/70 line-through">${{ item.price }} MXN</span>
            <Badge variant="neo-neon" class="text-[10px] w-[75px] ml-auto mr-2">Incluido</Badge>
        </div>
        <div class="grid grid-cols-3 items-center text-center py-2">
            <span class="font-medium sm:justify-self-center">Total:</span>
            <span class="text-bull-cream/70 line-through">${{ calcProdPrice(items) }} MXN</span>
            <span></span>
        </div>
        <div v-if="items.length === 0" class="text-center text-bull-cream/70">
            No hay productos incluidos en este paquete.
        </div>
    </div>
</template>

<script setup>
import { ListChecks } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { usePackages } from '@/Pages/Bussines/Packages/Composables/usePackages';

const { items } = defineProps({
    items: {
        type: Array,
        required: true
    }
});

const { calcProdPrice } = usePackages({'products':items});
</script>