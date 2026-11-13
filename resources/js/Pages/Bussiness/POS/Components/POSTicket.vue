<script setup>
import NeoCard from '@/components/Common/NeoCard.vue';
import NeoInput from '@/components/Common/NeoInput.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { Badge } from '@/components/ui/badge';
import {
    Banknote,
    Loader2,
    Minus,
    Plus,
    Receipt,
    ShoppingBag,
    Trash2,
} from 'lucide-vue-next';

defineProps({
    cartItems: {
        type: Array,
        required: true,
    },
    totalItemsCount: {
        type: Number,
        required: true,
    },
    totalAmount: {
        type: Number,
        required: true,
    },
    form: {
        type: Object,
        required: true,
    },
    canCheckout: {
        type: Boolean,
        required: true,
    },
    canIncrementItem: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits([
    'increment',
    'decrement',
    'remove',
    'clear',
    'submit',
]);
</script>

<template>
    <div class="lg:sticky lg:top-6">
        <NeoCard bg="white" padding="p-6">
            <div class="flex items-center justify-between gap-2 border-b-4 border-bull-blue pb-4">
                <FormTitles
                    title="Ticket Pop-Up"
                    :subtitle="`${totalItemsCount} artículo(s) en la orden`"
                    :icon="Receipt"
                />
                <button
                    v-if="cartItems.length"
                    type="button"
                    @click="emit('clear')"
                    class="text-xs font-black uppercase text-red-600 hover:underline shrink-0"
                >
                    Vaciar
                </button>
            </div>

            <!-- Lista de Artículos Seleccionados -->
            <div v-if="!cartItems.length" class="py-10 text-center border-b-2 border-dashed border-bull-blue/20">
                <ShoppingBag class="mx-auto h-10 w-10 text-bull-blue/30" />
                <p class="mt-3 text-sm font-black uppercase text-bull-blue/60">
                    El ticket está vacío
                </p>
                <p class="text-xs font-medium text-bull-blue/50 mt-1">
                    Selecciona productos o paquetes del catálogo izquierdo para comenzar.
                </p>
            </div>

            <div v-else class="max-h-80 overflow-y-auto divide-y-2 divide-bull-blue/15 my-4 pr-1">
                <div
                    v-for="item in cartItems"
                    :key="item.uniqueKey"
                    class="py-3 flex items-start justify-between gap-3"
                >
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <Badge
                                :variant="item.type === 'bundle' ? 'neo-pink' : 'neo-neon'"
                                shape="square"
                                class="text-[9px] px-1.5 py-0"
                            >
                                {{ item.type === 'bundle' ? 'Paquete' : 'Prenda' }}
                            </Badge>
                            <p class="font-black text-sm uppercase text-bull-blue truncate">
                                {{ item.name }}
                            </p>
                        </div>
                        <p class="text-xs font-bold text-bull-blue/70 mt-1 leading-snug">
                            {{ item.details }}
                        </p>
                        <p class="text-xs font-black text-bull-pink mt-0.5">
                            ${{ item.price.toFixed(2) }} c/u
                        </p>
                    </div>

                    <!-- Controles +/- de cantidad usando uniqueKey -->
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button
                            type="button"
                            @click="emit('decrement', item.uniqueKey)"
                            class="h-8 w-8 flex items-center justify-center border-2 border-bull-blue bg-white hover:bg-bull-cream font-black text-bull-blue"
                            title="Restar 1"
                        >
                            <Minus class="h-3.5 w-3.5 stroke-[3]" />
                        </button>

                        <span class="w-7 text-center font-black text-sm text-bull-blue">
                            {{ item.quantity }}
                        </span>

                        <button
                            type="button"
                            :disabled="!canIncrementItem(item)"
                            @click="emit('increment', item.uniqueKey)"
                            class="h-8 w-8 flex items-center justify-center border-2 border-bull-blue bg-bull-neon hover:bg-bull-pink hover:text-white font-black text-bull-blue disabled:opacity-40 disabled:cursor-not-allowed"
                            title="Sumar 1"
                        >
                            <Plus class="h-3.5 w-3.5 stroke-[3]" />
                        </button>

                        <button
                            type="button"
                            @click="emit('remove', item.uniqueKey)"
                            class="ml-1 h-8 w-8 flex items-center justify-center border-2 border-bull-blue bg-red-50 hover:bg-red-600 text-red-600 hover:text-white transition-colors"
                            title="Eliminar del ticket"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Total de la Venta -->
            <div class="mt-4 bg-bull-blue text-bull-cream p-4 border-4 border-bull-blue shadow-[4px_4px_0px_0px_rgba(242,17,109,1)]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-black uppercase tracking-widest text-bull-cream/80">
                        Total a Cobrar
                    </span>
                    <span class="text-3xl font-black text-bull-neon">
                        ${{ totalAmount.toFixed(2) }} <small class="text-xs">MXN</small>
                    </span>
                </div>
            </div>

            <!-- Datos del Cliente (Email Obligatorio para Recibo / Marketing) -->
            <form @submit.prevent="emit('submit')" class="mt-6 flex flex-col gap-4">
                <NeoInput
                    id="pos-customer-email"
                    v-model="form.email"
                    type="email"
                    label="Correo del Cliente (Recibo y VIP Club)"
                    placeholder="cliente@correo.com"
                    :required="true"
                    :error="form.errors?.email || form.errors?.stock_error"
                    validation="email"
                />

                <NeoInput
                    id="pos-customer-name"
                    v-model="form.customer_name"
                    type="text"
                    label="Nombre del Cliente / Bulldog (Opcional)"
                    placeholder="Venta Pop-Up"
                    :error="form.errors?.customer_name"
                />

                <!-- Botón Gigante e Inconfundible de COBRAR -->
                <button
                    type="submit"
                    :disabled="!canCheckout"
                    class="mt-2 w-full py-5 px-6 flex items-center justify-center gap-3 text-xl md:text-2xl font-black uppercase tracking-widest border-4 border-bull-blue transition-all duration-150"
                    :class="
                        canCheckout
                            ? 'bg-bull-neon text-bull-blue hover:bg-bull-pink hover:text-white shadow-[8px_8px_0px_0px_rgba(5,76,104,1)] hover:shadow-[3px_3px_0px_0px_rgba(5,76,104,1)] hover:translate-x-[5px] hover:translate-y-[5px] cursor-pointer'
                            : 'bg-slate-200 text-slate-400 border-slate-400 cursor-not-allowed'
                    "
                >
                    <Loader2 v-if="form.processing" class="h-7 w-7 animate-spin" />
                    <Banknote v-else class="h-7 w-7 stroke-[2.5]" />
                    <span>{{ form.processing ? 'PROCESANDO VENTA...' : 'COBRAR' }}</span>
                </button>
            </form>
        </NeoCard>
    </div>
</template>
