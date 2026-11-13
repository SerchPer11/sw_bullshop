<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    ClipboardList,
    Eye,
    Search,
    ShoppingBag,
} from 'lucide-vue-next';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import NeoInput from '@/components/Common/NeoInput.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import Pagination from '@/components/Common/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { formatDate } from '@/Hooks/useDates';
import { useFilters } from '@/Hooks/useFilters';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    orders: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', page: 1 }),
    },
});

const { filters } = useFilters(props.filters, 'order.staff.index');

const orders = computed(() => props.orders?.data ?? []);
const pagination = computed(() => props.orders?.meta ?? {});

const statusLabel = (status) => ({
    pending: 'Pendiente de entrega',
    preparing: 'En preparación',
    reserved: 'Reservada',
    completed: 'Completada',
    delivered: 'Entregada (POS)',
    cancelled: 'Cancelada',
    refunded: 'Reembolsada',
}[status] ?? status);

const paymentStatusLabel = (status) => ({
    paid: 'Pagado',
    pending: 'Pendiente de pago',
    refunded: 'Reembolsado',
    cancelled: 'Cancelado',
}[status] ?? status);

const statusVariant = (status) => status === 'completed' ? 'neo-neon' : 'neo-dark';

const paymentVariant = (status) => status === 'paid' ? 'neo-neon' : 'neo-pink';
</script>

<template>
    <Head :title="props.title" />

    <MainLayout>
        <section class="bg-bull-blue px-4 py-12 text-bull-cream">
            <div class="mx-auto flex max-w-7xl flex-col gap-4">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-bull-neon">BullShop · operación</p>
                <h1 class="text-4xl font-black uppercase tracking-tighter md:text-6xl">
                    Gestión de <span class="text-bull-neon">pedidos</span>
                </h1>
                <p class="max-w-2xl font-medium text-bull-cream/80">
                    Busca una orden para consultar sus detalles y actualizar su operación.
                </p>
            </div>
        </section>

        <section class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-8">
            <NeoCard bg="white-neon" padding="p-6">
                <FormTitles
                    title="Buscar pedidos"
                    subtitle="Filtra por número de orden, cliente, correo o estado"
                    :icon="Search"
                />
                <div class="mt-6">
                    <NeoInput
                        id="staff-order-search"
                        v-model="filters.search"
                        placeholder="BS-XXXX-XXXXXX, nombre, correo o estado"
                    />
                </div>
            </NeoCard>

            <div class="flex items-center justify-between gap-4">
                <p class="font-black uppercase tracking-wide text-bull-blue">
                    {{ pagination.total ?? 0 }}
                    {{ pagination.total === 1 ? 'pedido encontrado' : 'pedidos encontrados' }}
                </p>
                <ClipboardList class="h-7 w-7 text-bull-pink" />
            </div>

            <div v-if="!orders.length" class="flex flex-col items-center gap-4 py-8 text-center">
                <NeoCard bg="white-neon" padding="p-8" class="w-full">
                    <ShoppingBag class="mx-auto h-12 w-12 text-bull-pink" />
                    <h2 class="mt-4 text-2xl font-black uppercase text-bull-blue">
                        {{ filters.search ? 'Sin coincidencias' : 'Aún no hay pedidos' }}
                    </h2>
                    <p class="mt-2 font-medium text-bull-blue/70">
                        {{ filters.search ? 'Prueba con otro término de búsqueda.' : 'Cuando se registre una compra, aparecerá aquí.' }}
                    </p>
                </NeoCard>
            </div>

            <template v-else>
                <div class="grid gap-6 lg:grid-cols-2">
                    <NeoCard
                        v-for="order in orders"
                    :key="order.id"
                    bg="white"
                    padding="p-0"
                    class="overflow-hidden"
                    >
                        <div class="flex flex-col gap-4 border-b-4 border-bull-pink bg-bull-blue p-6 text-bull-cream">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center border-2 border-bull-blue bg-bull-neon text-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
                                        <ClipboardList class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-bull-cream/60">Orden</p>
                                        <h2 class="text-xl font-black">{{ order.order_number }}</h2>
                                    </div>
                                </div>
                                <p class="text-xl font-black text-bull-neon">${{ order.total_amount }}</p>
                            </div>
                            <p class="text-sm font-bold text-bull-cream/70">{{ formatDate(order.order_date?.raw) }}</p>
                        </div>

                        <div class="flex flex-col gap-5 p-6">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wide text-bull-blue/50">Cliente</p>
                                <p class="font-black text-bull-blue">{{ order.customer_name }}</p>
                                <p class="truncate text-sm font-medium text-bull-blue/70">{{ order.customer_email }}</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <Badge :variant="statusVariant(order.order_status)" shape="pill">
                                    {{ statusLabel(order.order_status) }}
                                </Badge>
                                <Badge :variant="paymentVariant(order.payment_status)" shape="pill">
                                    {{ paymentStatusLabel(order.payment_status) }}
                                </Badge>
                            </div>

                            <Button
                                variant="neo-neon"
                                size="lg"
                                class="w-full"
                                route-name="order.staff.show"
                                :parameter="order.order_number"
                            >
                                <Eye class="h-5 w-5" />
                                Ver detalle
                            </Button>
                        </div>
                    </NeoCard>
                </div>

                <Pagination
                    :links="pagination.links ?? []"
                    :total="pagination.total ?? 0"
                    :from="pagination.from ?? 0"
                    :to="pagination.to ?? 0"
                    :last-page="pagination.last_page ?? 1"
                    type-records="pedidos"
                />
            </template>
        </section>
    </MainLayout>
</template>