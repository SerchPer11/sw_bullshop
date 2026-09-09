<script setup>
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingBag, Star } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const discountPercentage = computed(() => {
    if (!props.product.originalPrice || props.product.originalPrice <= props.product.price) {
        return null;
    }
    const discount = ((props.product.originalPrice - props.product.price) / props.product.originalPrice) * 100;
    return Math.round(discount);
});
</script>

<template>
    <div :class="[
            'group relative border-4 flex flex-col transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]',
            product.isFeatured 
                ? 'bg-bull-pink border-bull-pink shadow-[8px_8px_0px_0px_rgba(0,39,49,1)]' 
                : 'bg-bull-cream border-bull-blue shadow-[6px_6px_0px_0px_rgba(0,39,49,1)]'
        ]
        ">
        
        <!-- Badges Superiores -->
        <div class="absolute top-3 left-3 z-10 flex flex-col gap-1 items-start">
            <Badge v-if="product.isNew" variant="neo-neon">Nuevo</Badge>
            <Badge v-if="product.stock > 0 && product.stock <= 5" variant="neo-pink" class="border border-bull-blue text-bull-cream">
                🔥 ¡Solo {{ product.stock }}!
            </Badge>
            <Badge v-if="product.stock === 0" variant="neo-dark">
                Sold Out
            </Badge>
        </div>

        <!-- BADGE GIGANTE PARA EL DESTACADO (Esquina superior derecha) -->
        <div v-if="product.isFeatured" class="absolute -top-4 -right-4 z-20 rotate-[10deg]">
            <span class="bg-bull-pink text-bull-neon font-black text-xs uppercase px-4 py-2 border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] flex items-center gap-1">
                <Star class="w-4 h-4 fill-bull-neon" /> Recomendado
            </span>
        </div>

        <!-- Imagen -->
        <div class="aspect-square w-full bg-bull-cream border-b-4 overflow-hidden p-4 relative"
            :class="[product.isFeatured ? 'border-bull-neon' : 'border-bull-blue']">
            <img :src="product.image" :alt="product.name" loading="lazy" 
                 class="w-full h-full object-contain transition-transform duration-300"
                 :class="{'group-hover:scale-105' : product.stock > 0, 'opacity-50 grayscale' : product.stock === 0}" 
            />
            
            <!-- Etiqueta de Descuento sobre la imagen -->
            <div v-if="discountPercentage" class="absolute bottom-3 left-3 bg-bull-blue text-bull-neon font-black text-sm px-2 py-1 rotate-[-3deg]">
                -{{ discountPercentage }}% OFF
            </div>
        </div>     

        <!-- Info -->
        <div class="p-4 flex flex-col flex-grow justify-between gap-4">
            <div>
                <h3 
                    :class="[
                        'font-black text-lg uppercase leading-tight line-clamp-2',
                        product.isFeatured ? 'text-white' : 'text-bull-blue'
                    ]"
                >
                    {{ product.name }}
                </h3>
                
                <!-- Lógica de Precios -->
                <div class="mt-2 flex items-baseline gap-2">
                    <span 
                        :class="[
                            'font-black text-xl',
                            product.isFeatured ? 'text-bull-neon' : 'text-bull-pink'
                        ]"
                    >
                        ${{ product.price }}
                    </span>
                    
                    <span v-if="product.originalPrice" class="text-sm font-bold line-through opacity-70" :class="product.isFeatured ? 'text-white' : 'text-bull-blue'">
                        ${{ product.originalPrice }}
                    </span>
                </div>
            </div>
            
            <Button 
                :variant="product.isFeatured ? 'neo-neon' : 'neo-blue'" 
                class="w-full"
                :disabled="product.stock === 0"
            >
                <ShoppingBag class="w-4 h-4 mr-2" />
                {{ product.stock === 0 ? 'Agotado' : 'Agregar' }}
            </Button>
        </div>
    </div>
</template>