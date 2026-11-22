<script setup>
import { Check, CreditCard, PackageCheck, RotateCcw } from 'lucide-vue-next';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import NeoConfirmModal from '@/components/Common/NeoConfirmModal.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

const showPayModal = defineModel('showPayModal', { type: Boolean, default: false });
const showDeliverModal = defineModel('showDeliverModal', { type: Boolean, default: false });
const showRefundModal = defineModel('showRefundModal', { type: Boolean, default: false });
const showCashCancelModal = defineModel('showCashCancelModal', { type: Boolean, default: false });

defineProps({
    order: {
        type: Object,
        required: true,
    },
    paymentLabel: {
        type: String,
        required: true,
    },
    paymentBadgeVariant: {
        type: String,
        required: true,
    },
    paymentStatusText: {
        type: String,
        required: true,
    },
    deliveryBadgeVariant: {
        type: String,
        required: true,
    },
    deliveryStatusText: {
        type: String,
        required: true,
    },
    canConfirmPayment: {
        type: Boolean,
        default: false,
    },
    canConfirmDelivery: {
        type: Boolean,
        default: false,
    },
    canRefundOrder: {
        type: Boolean,
        default: false,
    },
    canCancelCashOrder: {
        type: Boolean,
        default: false,
    },
    showAdminContactNotice: {
        type: Boolean,
        default: false,
    },
    isProcessing: {
        type: Boolean,
        default: false,
    },
    isCancelling: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['mark-payment', 'mark-delivery', 'refund-order', 'cancel-cash-order']);
</script>

<template>
    <div class="flex flex-col gap-6">
        <NeoCard bg="white-neon" padding="p-6">
            <FormTitles title="Operación" subtitle="Estado actual" :icon="PackageCheck" />

            <div class="mt-5 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <span class="font-bold text-bull-blue">Pago</span>
                    <Badge :variant="paymentBadgeVariant" shape="pill">
                        {{ paymentStatusText }}
                    </Badge>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="font-bold text-bull-blue">Entrega</span>
                    <Badge :variant="deliveryBadgeVariant" shape="pill">
                        {{ deliveryStatusText }}
                    </Badge>
                </div>

                <p class="flex items-center gap-2 border-t-2 border-bull-blue/20 pt-4 font-black text-bull-blue">
                    <CreditCard class="h-5 w-5 text-bull-pink" /> {{ paymentLabel }}
                </p>

                <p class="flex items-center justify-between border-t-4 border-bull-blue pt-4 text-xl font-black text-bull-blue">
                    <span>Total</span>
                    <span>${{ order.total_amount }}</span>
                </p>
            </div>
        </NeoCard>

        <!-- Confirmar pago en efectivo -->
        <NeoConfirmModal
            v-if="canConfirmPayment"
            v-model:open="showPayModal"
            title="¿Estás seguro de que quieres marcar este pedido como pagado?"
            description="Esta acción no se puede deshacer."
            confirmText="Sí, marcar como pagado"
            cancelText="Cancelar"
            variant="info"
            :loading="isProcessing"
            @confirm="emit('mark-payment')"
        >
            <template #trigger>
                <Button
                    variant="neo-neon"
                    size="xl"
                    :loading="isProcessing"
                    loadingText="Confirmando pago..."
                >
                    <Check class="h-5 w-5" /> Confirmar pago en efectivo
                </Button>
            </template>
        </NeoConfirmModal>

        <!-- Confirmar entrega -->
        <NeoConfirmModal
            v-if="canConfirmDelivery"
            v-model:open="showDeliverModal"
            title="¿Estás seguro de que quieres marcar este pedido como entregado?"
            description="Esta acción no se puede deshacer."
            confirmText="Sí, marcar como entregado"
            cancelText="Cancelar"
            variant="info"
            :loading="isProcessing"
            @confirm="emit('mark-delivery')"
        >
            <template #trigger>
                <Button
                    variant="neo-neon"
                    size="xl"
                    :loading="isProcessing"
                    loadingText="Confirmando entrega..."
                >
                    <PackageCheck class="h-5 w-5" /> Confirmar entrega
                </Button>
            </template>
        </NeoConfirmModal>

        <!-- Cancelar y reembolsar pedido de Stripe -->
        <NeoConfirmModal
            v-if="canRefundOrder"
            v-model:open="showRefundModal"
            title="¿Estás seguro de que quieres cancelar y reembolsar este pedido?"
            description="Esta acción no se puede deshacer."
            confirmText="Sí, cancelar y reembolsar"
            cancelText="Cancelar"
            variant="danger"
            :loading="isCancelling"
            @confirm="emit('refund-order')"
        >
            <template #trigger>
                <Button type="button" :disabled="isCancelling" variant="neo-pink" size="xl">
                    <RotateCcw class="h-5 w-5" :class="{ 'animate-spin': isCancelling }" />
                    <span>{{ isCancelling ? 'Procesando reembolso...' : 'Cancelar y Reembolsar' }}</span>
                </Button>
            </template>
        </NeoConfirmModal>

        <!-- Cancelar pedido en efectivo -->
        <NeoConfirmModal
            v-if="canCancelCashOrder"
            v-model:open="showCashCancelModal"
            title="¿Estás seguro de que quieres cancelar este pedido en efectivo?"
            description="Se cancelará el pedido y se restaurará el inventario. Esta acción no se puede deshacer."
            confirmText="Sí, cancelar pedido"
            cancelText="No cancelar"
            variant="danger"
            :loading="isCancelling"
            @confirm="emit('cancel-cash-order')"
        >
            <template #trigger>
                <Button type="button" :disabled="isCancelling" variant="neo-pink" size="xl">
                    <RotateCcw class="h-5 w-5" :class="{ 'animate-spin': isCancelling }" />
                    <span>{{ isCancelling ? 'Cancelando pedido...' : 'Cancelar pedido en efectivo' }}</span>
                </Button>
            </template>
        </NeoConfirmModal>

        <span
            v-if="showAdminContactNotice"
            class="text-justify text-sm font-bold text-gray-500/80"
        >
            Si se requiere cancelar este pedido, por favor contacta con un administrador.
        </span>
    </div>
</template>
