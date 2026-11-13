<script setup>
import { ChevronLeft, ChevronRight, Loader2 } from 'lucide-vue-next';
import { usePagination } from '@/Hooks/usePagination';

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        default: 0,
    },
    from: {
        type: Number,
        default: 0,
    },
    to: {
        type: Number,
        default: 0,
    },
    lastPage: {
        type: Number,
        default: 1,
    },
    typeRecords: {
        type: String,
        default: 'registros',
    },
    preserveScroll: {
        type: Boolean,
        default: true,
    },
    preserveState: {
        type: Boolean,
        default: true,
    },
});

const {
    getAriaLabel,
    getComponent,
    getLinkClasses,
    getLinkProps,
    isLoading,
    isNext,
    isPrevious,
} = usePagination(props);
</script>

<template>
    <div v-if="lastPage > 1" class="border-t-2 border-bull-blue/10 pt-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-0.5 text-bull-blue">
                <p class="text-xs font-black uppercase tracking-[0.15em] text-bull-blue/50">
                    {{ typeRecords }}
                </p>
                <p class="text-sm font-bold">
                    Mostrando <span class="font-black">{{ from }}–{{ to }}</span> de
                    <span class="font-black">{{ total }}</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span v-if="isLoading" class="flex items-center gap-2 text-xs font-black uppercase tracking-wide text-bull-pink">
                    <Loader2 class="h-4 w-4 animate-spin" />
                    Cargando
                </span>
                <nav v-if="links.length > 3" aria-label="Paginación">
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li v-for="({ url, label, active }, key) in links" :key="key">
                            <component
                                :is="getComponent(url)"
                                v-bind="getLinkProps(url)"
                                :class="getLinkClasses(url, label, active)"
                                :aria-label="getAriaLabel(label, active)"
                                :aria-current="active ? 'page' : undefined"
                            >
                                <ChevronLeft v-if="isPrevious(label)" class="h-5 w-5" aria-hidden="true" />
                                <ChevronRight v-else-if="isNext(label)" class="h-5 w-5" aria-hidden="true" />
                                <span v-else v-html="label"></span>
                            </component>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
