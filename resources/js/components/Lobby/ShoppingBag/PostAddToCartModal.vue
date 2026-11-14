<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                v-show="!isBagDrawerOpen"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-bull-blue/70 backdrop-blur-sm"
                @click.self="emit('close')"
            >
                <Transition
                    appear
                    enter-active-class="transition ease-out duration-200 transform"
                    enter-from-class="opacity-0 scale-90 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150 transform"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-90 translate-y-4"
                >
                    <div
                        v-if="show"
                        role="dialog"
                        aria-modal="true"
                        class="relative w-full max-w-lg bg-bull-cream border-4 border-bull-blue shadow-[10px_10px_0px_0px_rgba(0,39,49,1)] p-6 sm:p-8"
                    >
                        <!-- Botón X superior -->
                        <Button
                            variant="neo-white"
                            size="icon"
                            shape="square"
                            @click="emit('close')"
                            class="absolute top-4 right-4 w-9 h-9 hover:bg-bull-pink hover:text-white"
                            aria-label="Cerrar modal"
                        >
                            <X class="w-5 h-5" />
                        </Button>

                        <!-- Encabezado de Éxito -->
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-12 h-12 shrink-0 flex items-center justify-center bg-bull-neon border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]"
                            >
                                <Check class="w-7 h-7 text-bull-blue stroke-[3]" />
                            </div>
                            <div>
                                <span
                                    class="inline-block text-xs font-black uppercase tracking-widest bg-bull-pink text-white px-2 py-0.5 border border-bull-blue"
                                >
                                    Confirmación
                                </span>
                                <h2
                                    class="text-2xl sm:text-3xl font-black uppercase text-bull-blue leading-none mt-1 tracking-tight"
                                >
                                    ¡Añadido a tu bolsa!
                                </h2>
                            </div>
                        </div>

                        <!-- Resumen del Artículo Añadido -->
                        <div
                            v-if="item"
                            class="bg-white border-4 border-bull-blue shadow-[6px_6px_0px_0px_rgba(0,39,49,1)] p-4 mb-6 flex gap-4 items-center"
                        >
                            <img
                                v-if="item.image"
                                :src="item.image"
                                :alt="item.name"
                                class="w-20 h-20 object-cover border-2 border-bull-blue shrink-0 bg-bull-cream"
                            />
                            <div class="flex-1 min-w-0">
                                <h3 class="font-black text-lg uppercase text-bull-blue truncate">
                                    {{ item.name }}
                                </h3>
                                <p class="text-sm font-bold text-bull-blue/80 uppercase mt-0.5">
                                    Variante: <span class="text-bull-pink">{{ item.variantName }}</span>
                                </p>
                                <p v-if="item.quantity > 1" class="text-xs font-bold text-bull-blue/60 uppercase mt-0.5">
                                    Cantidad: {{ item.quantity }}
                                </p>
                                <div class="mt-2 pt-2 border-t-2 border-dashed border-bull-blue/20 flex items-center justify-between flex-wrap gap-2">
                                    <span class="text-xs font-black uppercase text-bull-blue/70">Subtotal artículo</span>
                                    <span class="text-xl font-black text-bull-pink">
                                        ${{ Number(item.subtotal || 0).toFixed(2) }} MXN
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones (Botones Neo-Brutalistas con Button y BagDrawer) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Botón 1 (Secundario / Outline) -->
                            <Button
                                variant="neo-blue-ghost"
                                size="lg"
                                shape="square"
                                class="w-full h-12"
                                @click="emit('close')"
                            >
                                SEGUIR NAVEGANDO
                            </Button>

                            <!-- Botón 2 (Primario / Llamativo - Abre el BagDrawer directamente) -->
                            <BagDrawer
                                variant="neo-pink"
                                size="lg"
                                shape="square"
                                :showable="false"
                                label="VER BOLSA"
                                class="w-full h-12"
                                @open-change="handleBagOpenChange"
                            />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Check, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import BagDrawer from '@/components/Lobby/ShoppingBag/BagDrawer.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const isBagDrawerOpen = ref(false);

watch(
    () => props.show,
    (newVal) => {
        if (!newVal) {
            isBagDrawerOpen.value = false;
        }
    }
);

const handleBagOpenChange = (open) => {
    isBagDrawerOpen.value = open;
    if (!open) {
        emit('close');
    }
};
</script>
