<template>
    <div class="mt-8" v-if="items.length > 0">
        <Separator title="Productos Recomendados" />

        <div class="flex justify-center overflow-x-auto space-x-4 items-center">
            <Carousel
                :items="items"
                :show-controls="items.length > 1"
                :itemsToShow="valorProp"
                gap="gap-4 md:gap-6 lg:gap-8"
                class="[--carousel-gap:24px] lg:[--carousel-gap:32px]"
                autoplay
                itemsToShow="3"
            >
                <template #default="{ item: product }">
                    <ProductCard
                        :product="product"
                        route="/"
                        :isFeatured="false"
                        routeName="package.show"
                    />
                </template>
            </Carousel>
        </div>
    </div>

</template>

<script setup>
    import { computed } from 'vue';
    import Separator from '@/components/Common/Separator.vue';
    import ProductCard from '@/components/Lobby/ProductCard.vue';
    import Carousel from '@/components/Common/Carousel.vue';
    import { useBreakpoints, breakpointsTailwind } from '@vueuse/core'

    const breakpoints = useBreakpoints(breakpointsTailwind);

    const props = defineProps({
        items: {
            type: Object,
            required: true
        }
    });

    const items = props.items?.data;

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