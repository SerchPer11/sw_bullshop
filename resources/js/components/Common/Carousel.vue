<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import useEmblaCarousel from 'embla-carousel-vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    items: {
        type: Array,
        required: true
    },
    options: {
        type: Object,
        default: () => ({ loop: true })
    },
    showControls: {
        type: Boolean,
        default: true
    },
    showIndicators: {
        type: Boolean,
        default: true
    },
    itemsToShow: {
        type: Number,
        default: 1,
        validator: (value) => Number.isInteger(value) && value >= 1
    },
    gap: {
        type: String,
        default: 'gap-0'
    },
    autoplay: {
        type: Boolean,
        default: false
    },
    autoplayInterval: {
        type: Number,
        default: 3000
    },
    showQuantity: {
        type: Boolean,
        default: false
    }
});

const [emblaRef, emblaApi] = useEmblaCarousel(props.options);
const selectedIndex = ref(0);
let autoplayTimer;

const stopAutoplay = () => {
    if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = undefined;
    }
};

const startAutoplay = () => {
    stopAutoplay();

    if (!props.autoplay || props.items.length < 2 || !emblaApi.value) return;

    autoplayTimer = setInterval(() => {
        emblaApi.value?.scrollNext();
    }, props.autoplayInterval);
};

watch(emblaApi, (api, _, onCleanup) => {
    if (!api) return;

    const updateSelectedIndex = () => {
        selectedIndex.value = api.selectedScrollSnap();
    };

    updateSelectedIndex();
    api.on('select', updateSelectedIndex);
    api.on('reInit', updateSelectedIndex);
    startAutoplay();

    onCleanup(() => {
        api.off('select', updateSelectedIndex);
        api.off('reInit', updateSelectedIndex);
        stopAutoplay();
    });
}, { immediate: true });

watch(() => [props.autoplay, props.autoplayInterval, props.items.length], startAutoplay);

onBeforeUnmount(stopAutoplay);

const scrollTo = (index) => {
    emblaApi.value?.scrollTo(index);
};
</script>

<template>
    <div
        class="group relative w-full [--carousel-gap:0px]"
        @mouseenter="stopAutoplay"
        @mouseleave="startAutoplay"
        @focusin="stopAutoplay"
        @focusout="startAutoplay"
    >
        <div v-if="showQuantity && items.length > 1" class="absolute bottom-4 right-2 z-20 text-sm font-medium text-bull-blue bg-bull-neon px-2 py-1 rounded border border-bull-blue shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]">
            {{ selectedIndex + 1 }} / {{ items.length }}
        </div>
        <div ref="emblaRef" class="overflow-hidden px-2 pb-10 pt-5">
            <div
                :class="['flex touch-pan-y', gap]"
            >
                <div
                    v-for="(item, index) in items"
                    :key="item?.id ?? index"
                    class="min-w-0"
                    :style="{
                        flex: `0 0 calc((100% - (var(--carousel-gap) * ${itemsToShow - 1})) / ${itemsToShow})`
                    }"
                >
                    <!-- Slot -->
                    <slot :item="item" :index="index" />
                </div>
            </div>
        </div>

        <!-- Botones -->
        <template v-if="showControls && items.length > itemsToShow">
            <button
                type="button"
                aria-label="Anterior"
                class="absolute left-2 top-1/2 z-20 flex h-9 w-9 -translate-y-1/2 items-center justify-center border-2 border-bull-blue bg-bull-neon text-bull-blue opacity-100 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] transition-[opacity,transform] hover:-translate-x-0.5 md:pointer-events-none md:opacity-0 md:group-hover:pointer-events-auto md:group-hover:opacity-100 md:focus-visible:pointer-events-auto md:focus-visible:opacity-100"
                @click="emblaApi?.scrollPrev()"
            >
                <ChevronLeft class="h-5 w-5" />
            </button>
            <button
                type="button"
                aria-label="Siguiente"
                class="absolute right-2 top-1/2 z-20 flex h-9 w-9 -translate-y-1/2 items-center justify-center border-2 border-bull-blue bg-bull-neon text-bull-blue opacity-100 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] transition-[opacity,transform] hover:translate-x-0.5 md:pointer-events-none md:opacity-0 md:group-hover:pointer-events-auto md:group-hover:opacity-100 md:focus-visible:pointer-events-auto md:focus-visible:opacity-100"
                @click="emblaApi?.scrollNext()"
            >
                <ChevronRight class="h-5 w-5" />
            </button>
        </template>

        <div v-if="showIndicators && items.length > itemsToShow" class="absolute bottom-2 left-1/2 z-20 flex -translate-x-1/2 gap-1.5 md:hidden">
            <button
                v-for="(_, index) in items"
                :key="index"
                type="button"
                :aria-label="`Ir al elemento ${index + 1}`"
                :class="[
                    'h-2.5 w-2.5 border border-bull-blue transition-colors',
                    selectedIndex === index ? 'bg-bull-pink' : 'bg-bull-neon'
                ]"
                @click="scrollTo(index)"
            />
        </div>
    </div>
</template>