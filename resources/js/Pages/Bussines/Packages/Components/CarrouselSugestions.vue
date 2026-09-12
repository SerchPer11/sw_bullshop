<template>
    <div class="mt-8" v-if="items.length > 0">
        <Separator title="Productos Recomendados" />

        <div class="flex justify-center overflow-x-auto space-x-4 items-center">
            <div v-for="item in items" :key="item.id" class="flex-shrink-0 w-64 bg-bull-cream border-4 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] p-4">
                <!-- Aquí irá la imagen dinámica del producto -->
                <img src="/Images/Lobby/img_hero2.webp" :alt="item.name" class="w-full h-40 object-cover mb-2 border-2 border-bull-blue/20" />
                <h3 class="text-lg font-bold text-bull-blue">{{ item.name }}</h3>
                <p class="text-sm text-bull-blue/80">${{ item.price }} MXN</p>
                <Button variant="neo-pink" size="sm" class="mt-2 w-full text-sm flex items-center justify-center gap-2">
                    <ShoppingBag class="w-4 h-4" />
                    Apartar Producto
                </Button>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { Button } from '@/components/ui/button';
    import { ShoppingBag } from 'lucide-vue-next';
    import Separator from '@/components/Common/Separator.vue';

    const props = defineProps({
        items: {
            type: Object,
            required: true
        }
    });

    const items = props.items.data;
</script>