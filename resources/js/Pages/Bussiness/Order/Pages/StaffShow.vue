<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Check, CreditCard, Mail, PackageCheck, Phone, Receipt, RotateCcw, User, Undo2 } from 'lucide-vue-next';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import NeoConfirmModal from '@/components/Common/NeoConfirmModal.vue';

const props = defineProps({
    title: { type: String, required: true },
    order: { type: Object, required: true },
});

const showModal = ref(false);
const showCashCancelModal = ref(false);
const showModalDeliver = ref(false);
const showModalPay = ref(false);
const isCancelling = ref(false);

const page = usePage();
const isAdmin = computed(() => page?.props?.auth?.roles?.includes('admin'));

const currentOrder = computed(() => props.order?.data ?? props.order);
const form = useForm({});

const items = computed(() => currentOrder.value?.items?.data ?? currentOrder.value?.items ?? []);

const itemName = (item) => item.products?.data?.name
    ?? item.products?.name
    ?? item.packages?.data?.name
    ?? item.packages?.name
    ?? 'Artículo';

const paymentLabel = computed(() => {
    if (currentOrder.value?.payment_method === 1) return 'Efectivo en evento';
    if (currentOrder.value?.payment_method === 3) return 'POS Pop-Up';
    return 'Tarjeta (Stripe)';
});

const canRefundOrder = computed(() => {
    const isStripe = currentOrder.value?.payment_method === 2 || currentOrder.value?.payment_method === 'stripe';
    const isNotCancelledOrRefunded =
        currentOrder.value?.order_status !== 'cancelled' &&
        currentOrder.value?.payment_status !== 'refunded' &&
        currentOrder.value?.payment_status !== 'cancelled';

    return isAdmin.value && isStripe && currentOrder.value?.payment_status === 'paid' && isNotCancelledOrRefunded;
});

const canCancelCashOrder = computed(() =>
    isAdmin.value
    && currentOrder.value?.payment_method === 1
    && currentOrder.value?.payment_status === 'pending'
    && !['cancelled', 'completed'].includes(currentOrder.value?.order_status),
);

const markPayment = () => {
    form.patch(route('order.staff.payment', currentOrder.value.order_number), {
        preserveScroll: true,
    });
};

const markDelivery = () => {
    form.patch(route('order.staff.delivery', currentOrder.value.order_number), {
        preserveScroll: true,
    });
};

const handleRefund = () => {
    isCancelling.value = true;
    router.post(
        route('admin.orders.refund', currentOrder.value.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
            },
            onFinish: () => {
                isCancelling.value = false;
            },
        }
    );
};

const cancelCashOrder = () => {
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
};
</script>

<template>

    <Head :title="props.title" />

    <MainLayout>
        <section class="bg-bull-blue px-4 py-10 text-bull-cream">
            <div class="mx-auto flex max-w-7xl flex-col gap-3">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-bull-neon">BullShop · operación</p>
                <h1 class="text-4xl font-black uppercase tracking-tighter md:text-6xl">
                    Orden <span class="text-bull-neon">{{ currentOrder.order_number }}</span>
                </h1>
                <p class="font-medium text-bull-cream/80">Consulta y confirma esta orden durante la entrega.</p>
            </div>
        </section>

        <div class="flex items-center gap-4 mt-4 max-w-7xl mx-auto px-4">
            <Button variant="neo-blue-ghost" routeName="order.staff.index" shape="pill">
                <Undo2 />
                <span class="sm:hidden">Volver</span>
                <span class="hidden sm:block">Volver</span>
            </Button>
        </div>

        <section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1.4fr_0.8fr]">
            <NeoCard bg="white" padding="p-6">
                <FormTitles title="Artículos" subtitle="Contenido de la orden" :icon="Receipt" />
                <div class="mt-6 flex flex-col gap-4">
                    <div v-for="item in items" :key="item.id" class="border-2 border-bull-blue p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase text-bull-pink">{{ item.products ? 'Producto' :
                                    'Paquete' }}</p>
                                <h2 class="text-lg font-black text-bull-blue">{{ itemName(item) }}</h2>
                                <p class="text-sm font-bold text-bull-blue/70">Cantidad: {{ item.quantity }}</p>
                            </div>
                            <p class="font-black text-bull-blue">${{ item.unit_price }}</p>
                        </div>
                    </div>
                </div>
            </NeoCard>

            <div class="flex flex-col gap-8">
                <NeoCard bg="white" padding="p-6">
                    <FormTitles title="Cliente" subtitle="Datos de la orden" :icon="User" />
                    <div class="mt-5 flex flex-col gap-3 text-sm font-bold text-bull-blue">
                        <p>{{ currentOrder.customer_name }}</p>
                        <p class="flex items-center gap-2">
                            <Mail class="h-4 w-4 text-bull-pink" />{{ currentOrder.customer_email }}
                        </p>
                        <p v-if="currentOrder.customer_phone" class="flex items-center gap-2">
                            <Phone class="h-4 w-4 text-bull-pink" />{{ currentOrder.customer_phone }}
                        </p>
                    </div>
                </NeoCard>

                <NeoCard bg="white-neon" padding="p-6">
                    <FormTitles title="Operación" subtitle="Estado actual" :icon="PackageCheck" />
                    <div class="mt-5 flex flex-col gap-4">
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-bold text-bull-blue">Pago</span>
                            <Badge :variant="currentOrder.payment_status === 'paid' ? 'neo-neon' : 'neo-pink'"
                                shape="pill">
                                {{
                                    currentOrder.payment_status === 'paid'
                                        ? 'Pagado'
                                        : currentOrder.payment_status === 'refunded'
                                            ? 'Reembolsado'
                                            : currentOrder.payment_status === 'cancelled'
                                                ? 'Cancelado'
                                                : 'Pendiente'
                                }}
                            </Badge>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-bold text-bull-blue">Entrega</span>
                            <Badge :variant="['completed', 'delivered'].includes(currentOrder.order_status) ? 'neo-neon' : 'neo-dark'"
                                shape="pill">
                                {{
                                    ['completed', 'delivered'].includes(currentOrder.order_status)
                                        ? 'Completada'
                                        : currentOrder.order_status === 'cancelled'
                                            ? 'Cancelada'
                                            : 'Pendiente'
                                }}
                            </Badge>
                        </div>
                        <p
                            class="flex items-center gap-2 border-t-2 border-bull-blue/20 pt-4 font-black text-bull-blue">
                            <CreditCard class="h-5 w-5 text-bull-pink" /> {{ paymentLabel }}
                        </p>
                        <p
                            class="flex items-center justify-between border-t-4 border-bull-blue pt-4 text-xl font-black text-bull-blue">
                            <span>Total</span>
                            <span>${{ currentOrder.total_amount }}</span>
                        </p>
                    </div>
                </NeoCard>

                <NeoConfirmModal
                    v-if="currentOrder.payment_method === 1 && currentOrder.payment_status !== 'paid' && currentOrder.order_status !== 'cancelled'"
                    v-model:open="showModalPay" title="¿Estás seguro de que quieres marcar este pedido como pagado?"
                    description="Esta acción no se puede deshacer." confirmText="Sí, marcar como pagado"
                    cancelText="Cancelar" variant="info" :loading="form.processing" @confirm="markPayment">
                    <template #trigger>
                        <Button
                            v-if="currentOrder.payment_method === 1 && currentOrder.payment_status !== 'paid' && currentOrder.order_status !== 'cancelled'"
                            variant="neo-neon" size="xl" :loading="form.processing" loadingText="Confirmando pago...">
                            <Check class="h-5 w-5" /> Confirmar pago en efectivo
                        </Button>
                    </template>
                </NeoConfirmModal>
                <NeoConfirmModal
                    v-if="currentOrder.payment_status === 'paid' && !['completed', 'delivered', 'cancelled'].includes(currentOrder.order_status)"
                    v-model:open="showModalDeliver" title="¿Estás seguro de que quieres marcar este pedido como entregado?"
                    description="Esta acción no se puede deshacer." confirmText="Sí, marcar como entregado"
                    cancelText="Cancelar" variant="info" :loading="form.processing" @confirm="markDelivery">
                    <template #trigger>
                        <Button
                            v-if="currentOrder.payment_status === 'paid' && !['completed', 'delivered', 'cancelled'].includes(currentOrder.order_status)"
                            variant="neo-neon" size="xl" :loading="form.processing" loadingText="Confirmando entrega...">
                            <PackageCheck class="h-5 w-5" /> Confirmar entrega
                        </Button>
                    </template>
                </NeoConfirmModal>

                <!-- Cancelación de Stripe y cancelación de pedidos en efectivo -->
                <NeoConfirmModal v-model:open="showModal" v-if="canRefundOrder"
                    title="¿Estás seguro de que quieres cancelar y reembolsar este pedido?"
                    description="Esta acción no se puede deshacer." confirmText="Sí, cancelar y reembolsar"
                    cancelText="Cancelar" variant="danger" :loading="isCancelling" @confirm="handleRefund">
                    <template #trigger>
                        <Button v-if="canRefundOrder" type="button" :disabled="isCancelling" variant="neo-pink"
                            size="xl">
                            <RotateCcw class="h-5 w-5" :class="{ 'animate-spin': isCancelling }" />
                            <span>{{ isCancelling ? 'Procesando reembolso...' : 'Cancelar y Reembolsar'
                            }}</span>
                        </Button>
                    </template>
                </NeoConfirmModal>
                <NeoConfirmModal v-model:open="showCashCancelModal" v-if="canCancelCashOrder"
                    title="¿Estás seguro de que quieres cancelar este pedido en efectivo?"
                    description="Se cancelará el pedido y se restaurará el inventario. Esta acción no se puede deshacer."
                    confirmText="Sí, cancelar pedido" cancelText="No cancelar" variant="danger"
                    :loading="isCancelling" @confirm="cancelCashOrder">
                    <template #trigger>
                        <Button v-if="canCancelCashOrder" type="button" :disabled="isCancelling"
                            variant="neo-pink" size="xl">
                            <RotateCcw class="h-5 w-5" :class="{ 'animate-spin': isCancelling }" />
                            <span>{{ isCancelling ? 'Cancelando pedido...' : 'Cancelar pedido en efectivo'
                            }}</span>
                        </Button>
                    </template>
                </NeoConfirmModal>
            </div>
        </section>
    </MainLayout>
</template>
