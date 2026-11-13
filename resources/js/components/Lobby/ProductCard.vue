<script setup>
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingBag, Star, Info, ListPlus, PackageOpen, Flame, Sparkles, Ban } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { useCartStore } from '@/Stores/useCartStore';
import { error422, messageSuccess } from '@/lib/alerts';
import { useLoading } from '@/Hooks/useLoading';
import Carousel from '@/components/Common/Carousel.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    routeName: {
        type: String,
        required: false
    },
    isFeatured: {
        type: Boolean,
        default: true
    }
});

const { isLoading, setLoading } = useLoading();

const cartStore = useCartStore();

const directVariant = computed(() => props.product.variants?.[0]);

const addToCart = () => {
    setLoading(true);

    if (props.product.has_multiple_options || props.product.is_bundle) {
        return;
    }

    if (!directVariant.value) {
        error422('Error al agregar al carrito', 'Este producto no tiene una variante disponible.');
        return;
    }

    cartStore.addVariant(props.product, directVariant.value);
    setTimeout(() => {
        setLoading(false);
        messageSuccess('Producto agregado al carrito', 'El producto ha sido agregado a tu carrito exitosamente.');
    }, 100);

};

const discountPercentage = computed(() => {

    // CAMBIO 1: Usar display_price en lugar de price
    if (!props.product.display_price || !props.product.compare_at_price || props.product.compare_at_price <= props.product.display_price) {
        return null;
    }
    const discount = ((props.product.compare_at_price - props.product.display_price) / props.product.compare_at_price) * 100;
    return Math.round(discount);
});
</script>

<template>
    <div :class="[
        'group relative border-4 flex flex-col transition-all h-full',
        product.is_featured && isFeatured
            ? 'bg-bull-pink border-bull-pink shadow-[8px_8px_0px_0px_rgba(0,39,49,1)]'
            : 'bg-bull-neon border-bull-blue shadow-[6px_6px_0px_0px_rgba(0,39,49,1)]'
    ]">

        <!-- Badges Superiores -->
        <div class="absolute top-3 left-3 z-10 flex flex-col gap-1 items-start">
            <Badge v-if="product.is_new" variant="neo-neon" shape="pill" class="gap-1">
                <Sparkles class="w-4 h-4 " />Nuevo
            </Badge>
            <Badge v-if="!product.is_bundle && product.stock > 0 && product.stock <= 5" variant="neo-pink" shape="pill"
                class="border border-bull-blue text-bull-cream gap-2">
                <Flame class="h-4 w-4 text-bull-cream" /> ¡Solo {{ product.stock }}!
            </Badge>
            <Badge v-if="product.stock === 0" variant="neo-dark" shape="pill" class="gap-2">
                <Ban class="h-5 w-5 text-white" />
                Agotado
            </Badge>
            <Badge v-if="product.is_bundle" variant="neo-blue" shape="pill" class="gap-2">
                <PackageOpen class="h-5 w-5 text-bull-neon/80" />
                Paquete
            </Badge>
        </div>

        <!-- BADGE GIGANTE PARA EL DESTACADO -->
        <div v-if="product.is_featured && isFeatured" class="absolute -top-4 -right-4 z-20">
            <span
                class="bg-bull-pink text-bull-neon font-black text-xs uppercase px-4 py-2 border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] flex items-center gap-1">
                <Star class="w-4 h-4 fill-bull-neon" /> Recomendado
            </span>
        </div>

        <!-- Imagen -->
        <div class="aspect-square w-full bg-bull-cream border-b-4 overflow-hidden p-4 relative"
            :class="[product.is_featured && isFeatured ? 'border-bull-pink/50' : 'border-bull-blue']">
            <!-- CAMBIO 2: product.photos[0]?.url para leer de AWS -->

            <Carousel :items="product.photos ?? product.photos[0]" :show-controls="product.photos.length > 1"
                showQuantity :itemsToShow="Number(1)" gap="gap-4 md:gap-6 lg:gap-8"
                class="[--carousel-gap:24px] lg:[--carousel-gap:32px]" autoplay>
                <template #default="{ item: photo }">
                    <img :src="photo.url" :alt="product.name" loading="lazy"
                        class="w-full h-full object-contain transition-transform duration-300"
                        :class="{ 'group-hover:scale-105': product.stock > 0 || product.is_bundle, 'opacity-50 grayscale': product.stock === 0 }" />
                </template>
            </Carousel>

            <!-- Etiqueta de Descuento -->
            <div v-if="discountPercentage"
                class="absolute bottom-3 left-3 bg-bull-blue text-bull-neon font-black text-sm px-2 py-1 border-2 border-bull-neon/50 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]">
                -{{ discountPercentage }}% OFF
            </div>
        </div>

        <!-- Info -->
        <div class="p-4 flex flex-col flex-grow justify-between gap-4">
            <div>
                <h3 :class="[
                    'font-black text-lg uppercase leading-tight line-clamp-2',
                    product.is_featured && isFeatured ? 'text-white' : 'text-bull-blue'
                ]">
                    {{ product.name }}
                </h3>

                <!-- Lógica de Precios -->
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-sm font-bold opacity-80"
                        :class="product.is_featured && isFeatured ? 'text-white' : 'text-bull-blue'"
                        v-if="product.has_multiple_options && !product.is_bundle">
                        Desde
                    </span>
                    <span :class="[
                        'font-black text-xl',
                        product.is_featured && isFeatured ? 'text-bull-neon' : 'text-bull-pink'
                    ]">
                        <!-- CAMBIO 3: Usar display_price -->
                        ${{ product.display_price }}
                    </span>

                    <span v-if="product.compare_at_price" class="text-sm font-bold line-through opacity-70"
                        :class="product.is_featured && isFeatured ? 'text-white' : 'text-bull-blue'">
                        ${{ product.compare_at_price }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <!-- Botón Principal: Si tiene opciones usa la ruta, si no, es para agregar directo -->
                <Button :variant="product.is_featured && isFeatured ? 'neo-neon' : 'neo-blue'" class="w-full"
                    :disabled="product.stock === 0"
                    :routeName="product.has_multiple_options || product.is_bundle ? routeName : null"
                    :parameter="product.has_multiple_options || product.is_bundle ? product.slug : null"
                    @click="addToCart" :loading="isLoading">
                    <ListPlus
                        v-if="product.has_multiple_options && product.stock > 0 || product.is_bundle && product.stock > 0"
                        class="w-4 h-4 mr-2" />
                    <ShoppingBag v-else-if="product.stock > 0" class="w-4 h-4 mr-2" />
                    {{ (product.stock === 0) ? 'Agotado' : ((product.has_multiple_options || product.is_bundle) ?
                        'Elegir opciones' : 'Agregar a la bolsa') }}
                </Button>

                <Button v-if="routeName"
                    :variant="product.is_featured && isFeatured ? 'neo-neon-ghost' : 'neo-blue-ghost'" class="w-full"
                    :routeName="`${routeName}`" :parameter="product.slug">
                    <Info class="w-4 h-4 mr-2" />
                    Más información
                </Button>
            </div>
        </div>
    </div>
</template>