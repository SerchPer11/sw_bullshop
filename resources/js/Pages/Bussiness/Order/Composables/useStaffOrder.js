import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { useCan, useRole } from '@/Hooks/usePermissions.js';
import { useOrderTracking } from './useOrderTracking';

export function useStaffOrder(props) {
    const {
        orderItems,
        packageProducts,
        itemName,
        itemType,
        itemImage,
        selectedVariantDetails,
        paymentLabel: getPaymentLabel,
        statusLabel,
        paymentStatusLabel,
    } = useOrderTracking(props);

    const showRefundModal = ref(false);
    const showCashCancelModal = ref(false);
    const showDeliverModal = ref(false);
    const showPayModal = ref(false);
    const isCancelling = ref(false);

    const form = useForm({});

    const currentOrder = computed(() => props.order?.data ?? props.order ?? {});
    const items = computed(() => orderItems(currentOrder.value));

    const isAdmin = computed(() => useRole('admin'));
    const canUpdateOrder = computed(() => useCan('order.staff.update'));
    const canDeleteOrder = computed(() => useCan('order.staff.delete'));

    const paymentLabel = computed(() => {
        if (currentOrder.value?.payment_method === 1) return 'Efectivo en evento';
        if (currentOrder.value?.payment_method === 3) return 'POS Pop-Up';
        return 'Tarjeta (Stripe)';
    });

    const paymentBadgeVariant = computed(() =>
        currentOrder.value?.payment_status === 'paid' ? 'neo-neon' : 'neo-pink'
    );

    const deliveryBadgeVariant = computed(() =>
        ['completed', 'delivered'].includes(currentOrder.value?.order_status) ? 'neo-neon' : 'neo-dark'
    );

    const deliveryStatusText = computed(() => {
        if (['completed', 'delivered'].includes(currentOrder.value?.order_status)) {
            return 'Completada';
        }
        if (currentOrder.value?.order_status === 'cancelled') {
            return 'Cancelada';
        }
        return 'Pendiente';
    });

    const canConfirmPayment = computed(() =>
        currentOrder.value?.payment_method === 1
        && currentOrder.value?.payment_status !== 'paid'
        && currentOrder.value?.order_status !== 'cancelled'
        && canUpdateOrder.value
    );

    const canConfirmDelivery = computed(() =>
        currentOrder.value?.payment_status === 'paid'
        && !['completed', 'delivered', 'cancelled'].includes(currentOrder.value?.order_status)
        && canUpdateOrder.value
    );

    const canRefundOrder = computed(() => {
        const isStripe = currentOrder.value?.payment_method === 2 || currentOrder.value?.payment_method === 'stripe';
        const isNotCancelledOrRefunded =
            currentOrder.value?.order_status !== 'cancelled'
            && currentOrder.value?.payment_status !== 'refunded'
            && currentOrder.value?.payment_status !== 'cancelled';

        return isAdmin.value
            && canDeleteOrder.value
            && isStripe
            && currentOrder.value?.payment_status === 'paid'
            && isNotCancelledOrRefunded;
    });

    const canCancelCashOrder = computed(() =>
        isAdmin.value
        && canDeleteOrder.value
        && currentOrder.value?.payment_method === 1
        && currentOrder.value?.payment_status === 'pending'
        && !['cancelled', 'completed'].includes(currentOrder.value?.order_status)
    );

    const showAdminContactNotice = computed(() =>
        !(canCancelCashOrder.value || canRefundOrder.value) && !isAdmin.value
    );

    function packageProductImage(packageProduct) {
        const photos = packageProduct?.photos?.data ?? packageProduct?.photos ?? [];
        return Array.isArray(photos) && photos[0]?.url ? photos[0].url : '/placeholder.png';
    }

    function markPayment() {
        form.patch(route('order.staff.payment', currentOrder.value.order_number), {
            preserveScroll: true,
            onSuccess: () => {
                showPayModal.value = false;
            },
        });
    }

    function markDelivery() {
        form.patch(route('order.staff.delivery', currentOrder.value.order_number), {
            preserveScroll: true,
            onSuccess: () => {
                showDeliverModal.value = false;
            },
        });
    }

    function handleRefund() {
        isCancelling.value = true;
        router.post(
            route('admin.orders.refund', currentOrder.value.id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    showRefundModal.value = false;
                },
                onFinish: () => {
                    isCancelling.value = false;
                },
            }
        );
    }

    function cancelCashOrder() {
        isCancelling.value = true;
        router.post(
            route('admin.orders.cancel-cash', currentOrder.value.id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    showCashCancelModal.value = false;
                },
                onFinish: () => {
                    isCancelling.value = false;
                },
            }
        );
    }

    return {
        // State & Form
        form,
        currentOrder,
        items,
        showRefundModal,
        showCashCancelModal,
        showDeliverModal,
        showPayModal,
        isCancelling,

        // Labels & Badges
        paymentLabel,
        paymentBadgeVariant,
        deliveryBadgeVariant,
        deliveryStatusText,
        statusLabel,
        paymentStatusLabel,
        getPaymentLabel,

        // Item Helpers
        itemName,
        itemType,
        itemImage,
        packageProducts,
        packageProductImage,
        selectedVariantDetails,

        // Permissions & Visibility
        canConfirmPayment,
        canConfirmDelivery,
        canRefundOrder,
        canCancelCashOrder,
        showAdminContactNotice,

        // Actions
        markPayment,
        markDelivery,
        handleRefund,
        cancelCashOrder,
    };
}
