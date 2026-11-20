<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import StaffOrderHeader from '../Components/StaffOrderHeader.vue';
import StaffOrderItemsCard from '../Components/StaffOrderItemsCard.vue';
import StaffOrderCustomerCard from '../Components/StaffOrderCustomerCard.vue';
import StaffOrderOperationCard from '../Components/StaffOrderOperationCard.vue';
import { useStaffOrder } from '../Composables/useStaffOrder';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    order: {
        type: Object,
        required: true,
    },
});

const {
    form,
    currentOrder,
    items,
    showRefundModal,
    showCashCancelModal,
    showDeliverModal,
    showPayModal,
    isCancelling,
    paymentLabel,
    paymentBadgeVariant,
    deliveryBadgeVariant,
    deliveryStatusText,
    paymentStatusLabel,
    itemName,
    itemType,
    itemImage,
    packageProducts,
    packageProductImage,
    selectedVariantDetails,
    canConfirmPayment,
    canConfirmDelivery,
    canRefundOrder,
    canCancelCashOrder,
    showAdminContactNotice,
    markPayment,
    markDelivery,
    handleRefund,
    cancelCashOrder,
} = useStaffOrder(props);
</script>

<template>
    <Head :title="props.title" />

    <MainLayout>
        <StaffOrderHeader :order-number="currentOrder.order_number" />

        <section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1.4fr_0.8fr]">
            <!-- Columna Izquierda: Artículos con imágenes y variantes -->
            <StaffOrderItemsCard
                :items="items"
                :item-name="itemName"
                :item-type="itemType"
                :item-image="itemImage"
                :package-products="packageProducts"
                :package-product-image="packageProductImage"
                :selected-variant-details="selectedVariantDetails"
            />

            <!-- Columna Derecha: Cliente y Operación / Acciones -->
            <div class="flex flex-col gap-8">
                <StaffOrderCustomerCard :order="currentOrder" />

                <StaffOrderOperationCard
                    v-model:show-pay-modal="showPayModal"
                    v-model:show-deliver-modal="showDeliverModal"
                    v-model:show-refund-modal="showRefundModal"
                    v-model:show-cash-cancel-modal="showCashCancelModal"
                    :order="currentOrder"
                    :payment-label="paymentLabel"
                    :payment-badge-variant="paymentBadgeVariant"
                    :payment-status-text="paymentStatusLabel(currentOrder.payment_status)"
                    :delivery-badge-variant="deliveryBadgeVariant"
                    :delivery-status-text="deliveryStatusText"
                    :can-confirm-payment="canConfirmPayment"
                    :can-confirm-delivery="canConfirmDelivery"
                    :can-refund-order="canRefundOrder"
                    :can-cancel-cash-order="canCancelCashOrder"
                    :show-admin-contact-notice="showAdminContactNotice"
                    :is-processing="form.processing"
                    :is-cancelling="isCancelling"
                    @mark-payment="markPayment"
                    @mark-delivery="markDelivery"
                    @refund-order="handleRefund"
                    @cancel-cash-order="cancelCashOrder"
                />
            </div>
        </section>
    </MainLayout>
</template>
