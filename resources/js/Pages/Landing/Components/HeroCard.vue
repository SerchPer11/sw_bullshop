<script setup>
import { computed } from 'vue';
import Carousel from '@/components/Common/Carousel.vue';

const props = defineProps({
    featPackageData: {
        type: Object,
        required: true
    },
    featProductData: {
        type: Object,
        required: true
    }
});

const normalizeItems = (source) => {
  const data = source?.data ?? source;
  if (!data) return [];
  return Array.isArray(data) ? data : [data];
};

const carouselItems = computed(() => [
  ...normalizeItems(props.featPackageData).map((item) => ({ ...item, type: 'package' })),
  ...normalizeItems(props.featProductData).map((item) => ({ ...item, type: 'product' }))
]);

const discountPercentage = (item) => {
  if (!item.price || item.compare_at_price <= item.price) {
        return null;
    }
  const discount = ((item.compare_at_price - item.price) / item.compare_at_price) * 100;
    return Math.round(discount);
};

const stockStatus = (item) => {
  if (item.stock > 0) {
    return item.stock <= 5 ? `🔥 Solo ${item.stock} en stock` : 'COMPARTE EL ESTILO CON TU COMPAÑERO';
    } else {
        return 'Sold Out';
    }
};

</script>

<template>
    <div class="relative w-full max-w-md mx-auto lg:max-w-[360px] xl:max-w-[400px] flex justify-center lg:justify-end mt-10 lg:mt-0">
        <Carousel :items="carouselItems" :show-controls="carouselItems.length > 1" autoplay >
            <template #default="{ item }">
                <div class="relative bg-bull-neon border-4 border-bull-blue shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] p-4 rounded-xl w-full aspect-[4/5]">
                    <div :class="['absolute -top-2 left-0 z-10 max-w-[calc(100%-1rem)] font-bold text-sm uppercase px-3 py-1.5 border-2 border-bull-blue shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] rotate-[-4deg]',
                        item.stock > 0 && item.stock <= 5
                            ? 'bg-bull-pink text-white'
                            : item.is_new && item.stock > 0 ? 'bg-bull-aqua text-white'
                            : 'bg-bull-blue text-bull-neon']
                    ">
                        <span class="text-xs">{{ item.stock > 0 ? stockStatus(item) : 'Sold Out' }}</span>
                    </div>

                    <div class="w-full h-full bg-bull-pink/20 border-2 border-bull-blue/20 rounded-lg overflow-hidden relative">
                        <img
                            :src="item.image ?? '/Images/Lobby/img_hero1.webp'"
                            :alt="item.name ?? 'Producto destacado BullShop'"
                            class="w-full h-full object-cover object-center"
                            loading="eager"
                        />
                        <div v-if="discountPercentage(item)" class="absolute top-3 right-3 bg-bull-blue text-bull-neon font-black text-sm px-2 py-1 border-2 border-bull-neon/50 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]">
                            -{{ discountPercentage(item) }}% OFF
                        </div>
                    </div>

                    <div class="absolute -bottom-6 right-4 left-4 bg-white border-4 border-bull-blue p-3 flex justify-between items-center shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
                        <div class="flex flex-col">
                            <span class="font-black text-sm uppercase">{{ item.name }}</span>
                            <span class="text-[10px] text-bull-blue/70 font-bold uppercase max-w-[220px] pb-1">{{ item.description }}</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-bull-pink font-black text-sm">${{ item.price }} MXN</span>
                            <span v-if="item.compare_at_price" class="text-xs font-bold line-through opacity-70">
                                ${{ item.compare_at_price }} MXN
                            </span>
                        </div>
                    </div>
                    <!--cambiar por boton para redirección-->
                    <div v-if="item.type === 'package'" class="absolute -bottom-10 left-0 z-10 bg-bull-pink text-white font-bold text-xs uppercase px-3 py-1.5 border-2 border-bull-blue shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] rotate-[2deg]">
                        ® Patrón Morfológico Registrado
                    </div>
                </div>
            </template>
        </Carousel>
      </div>
</template>