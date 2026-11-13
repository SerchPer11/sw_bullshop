<template>
    <!-- ESTADO VACÍO -->
    <div v-if="cartStore.items.length === 0"
        class="flex-grow flex flex-col items-center justify-center p-6 text-center">
        <ShoppingBag class="w-16 h-16 text-bull-blue/80 mb-4" />
        <p class="font-bold text-bull-blue/50 text-lg uppercase">Tu bolsa está vacía</p>
    </div>

    <!-- LISTA DE PRODUCTOS -->

    <ScrollArea v-else class="flex-grow p-4">
        <div class="flex flex-col gap-4 p-1">
            <div v-for="item in cartStore.items" :key="item.uniqueKey"
                class=" border-4 border-bull-blue px-2 py-2 rounded-md transition-all shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
                <!-- Imagen de AWS -->
                <div class="flex gap-4">
                    <Link
                        class="h-24 w-24 border-2 border-bull-blue bg-white flex-shrink-0 my-auto hover:translate-x-[2px] hover:translate-y-[2px] transition-all"
                        :href="item?.type == 'variant' ? route('product.show', item.slug) : route('package.show', item.slug)"
                        title="Ver detalles del producto">
                        <img :src="item.image" :alt="item.name" class="h-full w-full object-contain p-1" />
                    </Link>

                    <!-- Info del item -->
                    <div class="flex-grow flex flex-col justify-between text-wrap w-3/4">
                        <div>
                            <Link
                                :href="item?.type == 'variant' ? route('product.show', item.slug) : route('package.show', item.slug)"
                                :title="item.name">
                                <h4
                                    class="font-black text-lg uppercase text-bull-blue line-clamp-1 hover:underline hover:text-bull-blue/80 transition-all">
                                    {{ item.name }}
                                </h4>
                            </Link>
                            <p class="text-sm font-bold text-bull-blue/60">{{ item.details }}</p>
                            <p class="text-sm font-bold text-bull-blue/60">
                                <span v-if="item?.compared_price"
                                    class="text-xs font-bold text-bull-pink/50 line-through">
                                    ${{ item?.compared_price }}
                                </span>
                                ${{ item.price }}
                                <span class="text-xs">MXN</span>
                            </p>

                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-2">
                                <Button variant="neo-pink" size="icon" shape="rounded" @click="decreaseQuantity(item)"
                                    class="h-5 w-5">
                                    <Minus class="w-4 h-4" />
                                </Button>
                                <span class="text-sm font-bold text-bull-blue bg-bull-blue/10 px-2 py-1 rounded">
                                    {{ item.quantity }}
                                </span>
                                <Button :disabled="item.limit_per_user <= item.quantity && item.limit_per_user > 0"
                                    variant="neo-neon" size="icon" shape="rounded" @click="increaseQuantity(item)"
                                    class="h-5 w-5">
                                    <Plus class="w-4 h-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                    <Button variant="neo-pink" size="icon" shape="rounded" @click="cartStore.removeItem(item.uniqueKey)"
                        class="h-8 min-w-8 mr-2 mt-auto mb-auto">
                        <Trash2 class="w-4 h-4" />
                    </Button>
                </div>
            </div>
        </div>
    </ScrollArea>
</template>

<script setup>
import { ScrollArea } from '@/components/ui/scroll-area';
import Separator from '@/components/Common/Separator.vue';
import { Button } from '@/components/ui/button';
import { ShoppingBag, Plus, Minus, Trash2 } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    cartStore: {
        type: Object,
        required: true
    }
});

const increaseQuantity = (item) => {
    if (item.limit_per_user && item.quantity >= item.limit_per_user) {
        // Aquí podrías mostrar un mensaje al usuario indicando que ha alcanzado el límite
        return;
    }
    item.quantity += 1;
};

const decreaseQuantity = (item) => {
    if (item.quantity > 1) {
        item.quantity -= 1;
    } else {
        cartStore.removeItem(item.uniqueKey);
    }
};
</script>