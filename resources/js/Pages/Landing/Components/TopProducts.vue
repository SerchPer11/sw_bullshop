<script setup>
import { computed } from 'vue';
import Separator from '@/components/Common/Separator.vue';
import ProductCard from '@/components/Lobby/ProductCard.vue';
import Carousel from '@/components/Common/Carousel.vue';
import { useBreakpoints, breakpointsTailwind } from '@vueuse/core'

const props = defineProps({
    featPackageData: {
        type: Object,
        required: true
    },
    featProductsData: {
        type: Object,
        required: true
    }
});

const normalizeItems = (source) => {
    const data = source?.data ?? source;
    if (!data) return [];
    return Array.isArray(data) ? data : [data];
};

const topProducts = computed(() => [
    ...normalizeItems(props.featPackageData).map((item) => ({ ...item, type: 'package' })),
    ...normalizeItems(props.featProductsData).map((item) => ({ ...item, type: 'product' }))
]);

const breakpoints = useBreakpoints(breakpointsTailwind)

const valorProp = computed(() => {
    switch (true) {
        case breakpoints.lg.value:
            return 3;
        case breakpoints.md.value:
            return 2;
        case breakpoints.sm.value:
            return 1;
        default:
            return 1;
    }
})
</script>

<template>
    <section class="md:max-w-3xl lg:max-w-7xl mx-auto mt-12 mb-12 max-w-sm">
        <Separator title="Nuestros Productos Destacados" />
        <div class=" md:max-w-3xl lg:max-w-7xl mx-auto">
            <Carousel
                :items="topProducts"
                :show-controls="topProducts.length > 1"
                :itemsToShow="valorProp"
                gap="gap-4 md:gap-6 lg:gap-8"
                class="[--carousel-gap:24px] lg:[--carousel-gap:32px]"
                autoplay
            >
                <template #default="{ item: product }">
                    <ProductCard
                        :product="product"
                        route="/"
                        :isFeatured="false"
                        :routeName="product.is_bundle ? 'package.show' : 'product.show'"
                    />
                </template>
            </Carousel>
        </div>
    </section>
</template>