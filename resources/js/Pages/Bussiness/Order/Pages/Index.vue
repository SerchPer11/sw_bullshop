<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ChevronDown,
    ClipboardList,
    Clock3,
    CreditCard,
    Gift,
    Info,
    Mail,
    MapPin,
    PackageCheck,
    Receipt,
    Search,
    ShoppingBag,
    User,
    CircleDollarSign,
    Undo2
} from 'lucide-vue-next';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import Pagination from '@/components/Common/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import NeoInput from '@/components/Common/NeoInput.vue';
import { useOrderTracking } from '../Composables/useOrderTracking';
import { formatDate } from '@/Hooks/useDates';
import { useFilters } from '@/Hooks/useFilters';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    orders: {
        type: Object,
        default: null,
    },
    order: {
        type: Object,
        default: null,
    },
    isAuthenticated: {
        type: Boolean,
        default: false,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', page: 1 }),
    },
});

const { filters } = useFilters(props.filters, 'order.my-orders');

const {
    form,
    selectedOrderId,
    visibleOrders,
    guestOrder,
    submit,
    toggleOrder,
    orderItems,
    packageProducts,
    itemName,
    itemType,
    itemImage,
    selectedVariantDetails,
    paymentLabel,
    statusLabel,
    paymentStatusLabel,
    statusVariant,
    totalAmountColor,
} = useOrderTracking(props);

const pagination = computed(() => props.orders?.meta ?? {});
</script>

<template>

    <Head :title="props.title" />

    <MainLayout>
        <section class="bg-bull-blue px-4 py-12 text-bull-cream">
            <div class="mx-auto flex max-w-7xl flex-col gap-4">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-bull-neon">Bullshop · pedidos</p>
                <h1 class="text-4xl font-black uppercase tracking-tighter md:text-6xl">
                    Rastrea<span class="text-bull-neon"> tu orden</span>
                </h1>
                <p class="max-w-2xl font-medium text-bull-cream/80">
                    Consulta el estado, los artículos y las selecciones de cada pedido en un solo lugar.
                </p>
            </div>
        </section>
        <div class="flex items-center gap-4 mt-4 max-w-7xl mx-auto px-4">
            <Button variant="neo-blue-ghost" routeName="collection.index" shape="pill">
                <Undo2 />
                <span class="sm:hidden">Volver a colección</span>
                <span class="hidden sm:block">Volver a colección</span>
            </Button>
        </div>

        <section v-if="!props.isAuthenticated" class="bg-bull-neon px-4 py-4">
            <div
                class="mx-auto flex max-w-7xl items-center justify-center gap-3 text-center text-sm font-black uppercase tracking-wide text-bull-blue md:text-base">
                <Search class="h-6 w-6 flex-shrink-0" />
                <p>Ten a la mano tu número de orden y el correo usado en la compra.</p>
            </div>
        </section>

        <section class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-8">
            <NeoCard v-if="!props.isAuthenticated" bg="white" padding="p-6" class="mx-auto w-full">
                <FormTitles title="Encuentra tu pedido"
                    subtitle="Validaremos ambos datos antes de mostrar la información" :icon="Receipt" />

                <form class="mt-6 flex flex-col gap-5" @submit.prevent="submit">
                    <div class="grid gap-5 md:grid-cols-2">
                        <NeoInput id="order_number" v-model="form.order_number" label="Número de orden"
                            placeholder="BS-XXXX-XXXXXX" :error="form.errors.order_number" required />
                        <NeoInput id="email" v-model="form.email" label="Correo de compra" type="email"
                            placeholder="tu@correo.com" :error="form.errors.email" required />
                    </div>
                    <div class="flex justify-center md:justify-end">
                        <Button type="submit" variant="neo-blue" size="xl" class="w-full md:w-fit"
                            :loading="form.processing" loading-text="Buscando">
                            <Search class="h-5 w-5" />
                            Buscar pedido
                        </Button>
                    </div>
                </form>
            </NeoCard>

            <NeoCard v-if="props.isAuthenticated" bg="white-neon" padding="p-6" class="mx-auto w-full">
                <FormTitles
                    title="Buscar entre tus pedidos"
                    subtitle="Filtra por número de orden o estado"
                    :icon="Search"
                />
                <div class="mt-6">
                    <NeoInput
                        id="my-order-search"
                        v-model="filters.search"
                        placeholder="BS-XXXX-XXXXXX, pendiente, completado..."
                    />
                </div>
            </NeoCard>

            <div v-if="props.isAuthenticated && !visibleOrders.length"
                class="flex flex-col items-center gap-4 py-8 text-center">
                <NeoCard bg="white-neon" padding="p-8" class="w-full max-w-7xl">
                    <ShoppingBag class="mx-auto h-12 w-12 text-bull-pink" />
                    <h2 class="mt-4 text-2xl font-black uppercase text-bull-blue">
                        {{ filters.search ? 'Sin coincidencias' : 'Aún no tienes pedidos' }}
                    </h2>
                    <p class="mt-2 font-medium text-bull-blue/70">
                        {{ filters.search ? 'Prueba con otro término de búsqueda.' : 'Cuando realices una compra, aparecerá aquí.' }}
                    </p>
                </NeoCard>
            </div>

            <div v-if="visibleOrders.length" class="flex flex-col gap-8">
                <div class="grid gap-8 ">
                    <NeoCard v-for="currentOrder in visibleOrders" :key="currentOrder.id" bg="white" padding="p-0"
                        class="overflow-hidden">
                        <div
                            class="flex flex-col gap-6 bg-bull-blue border-b-4 border-bull-pink p-6 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 flex-shrink-0 items-center justify-center border-2 border-bull-blue bg-bull-neon text-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
                                    <ClipboardList class="h-7 w-7" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-xs font-black uppercase tracking-widest text-bull-cream/60">Orden</p>
                                    <h3 class="text-2xl font-black text-bull-cream">{{ currentOrder.order_number }}</h3>
                                    <p class="text-sm font-bold text-bull-cream/70">{{ formatDate(currentOrder.order_date?.raw) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-start gap-3 md:items-end">
                                <Badge :variant="statusVariant(currentOrder.order_status)" shape="pill">
                                    {{ statusLabel(currentOrder.order_status) }}
                                </Badge>
                                <p class="text-2xl font-black" :class="totalAmountColor(currentOrder.order_status)">${{ currentOrder.total_amount }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6 p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-b-2 border-bull-blue/10 pb-6">
                                <!-- 1. Estado del Pedido -->
                                <div class="flex items-center gap-3">
                                    <ClipboardList class="h-5 w-5 text-bull-pink shrink-0" />
                                    <div>
                                        <p class="text-xs font-bold uppercase text-bull-blue/50">Estado</p>
                                        <p class="font-black text-bull-blue leading-tight">{{
                                            statusLabel(currentOrder.order_status) }}</p>
                                    </div>
                                </div>

                                <!-- 2. Método de Pago -->
                                <div class="flex items-center gap-3">
                                    <CreditCard class="h-5 w-5 text-bull-pink shrink-0" />
                                    <div>
                                        <p class="text-xs font-bold uppercase text-bull-blue/50">Pago</p>
                                        <p class="font-black text-bull-blue leading-tight">{{
                                            paymentLabel(currentOrder.payment_method) }}</p>
                                    </div>
                                </div>

                                <!-- 3. Estado del Pago -->
                                <div class="flex items-center gap-3">
                                    <CircleDollarSign class="h-5 w-5 text-bull-pink shrink-0" />
                                    <div>
                                        <p class="text-xs font-bold uppercase text-bull-blue/50">Estado del pago</p>
                                        <p class="font-black text-bull-blue leading-tight">{{
                                            paymentStatusLabel(currentOrder.payment_status) }}</p>
                                    </div>
                                </div>

                                <!-- 4. Cantidad de Artículos -->
                                <div class="flex items-center gap-3">
                                    <ShoppingBag class="h-5 w-5 text-bull-pink shrink-0" />
                                    <div>
                                        <p class="text-xs font-bold uppercase text-bull-blue/50">Artículos</p>
                                        <p class="font-black text-bull-blue leading-tight">{{
                                            orderItems(currentOrder).length }}</p>
                                    </div>
                                </div>
                            </div>

                            <Button variant="neo-blue-ghost" class="w-full sm:w-fit"
                                @click="toggleOrder(currentOrder.id)">
                                {{ selectedOrderId === currentOrder.id ? 'Ocultar detalle' : 'Ver detalle completo' }}
                                <ChevronDown :class="{ 'rotate-180': selectedOrderId === currentOrder.id }"
                                    class="h-5 w-5 transition-transform duration-300" />
                            </Button>

                            <div v-if="selectedOrderId === currentOrder.id"
                                class="flex flex-col gap-8 border-t-4 border-bull-blue pt-6">
                                <div class="grid gap-8 lg:grid-cols-[1.4fr_0.8fr]">
                                    <div class="flex flex-col gap-4">
                                        <FormTitles title="Artículos del pedido"
                                            subtitle="Lo que apartaste en esta orden" :icon="ShoppingBag" />
                                        <div class="flex flex-col gap-4">
                                            <div v-for="item in orderItems(currentOrder)" :key="item.id"
                                                class="flex flex-col gap-4 border-2 border-bull-blue bg-white p-4 sm:flex-row">
                                                <img :src="itemImage(item)" :alt="itemName(item)"
                                                    class="h-24 w-24 flex-shrink-0 border-2 border-bull-blue object-cover">
                                                <div class="flex flex-1 flex-col gap-2">
                                                    <div class="flex flex-col justify-between gap-2 sm:flex-row">
                                                        <div>
                                                            <p class="text-xs font-black uppercase text-bull-pink">{{
                                                                itemType(item) }}</p>
                                                            <h4 class="text-lg font-black text-bull-blue">{{
                                                                itemName(item) }}</h4>
                                                        </div>
                                                        <p class="font-black text-bull-blue">${{ item.unit_price }} c/u
                                                        </p>
                                                    </div>
                                                    <p class="text-sm font-bold text-bull-blue/70">Cantidad: {{
                                                        item.quantity }}</p>

                                                    <div v-if="item.products" class="border-t border-bull-blue/10 pt-3 text-sm text-bull-blue/70">
                                                        <span class="font-bold text-bull-blue">Selección:</span>
                                                        {{ selectedVariantDetails(item).name }}
                                                        <span
                                                            v-if="selectedVariantDetails(item).attributes.length"
                                                            class="text-bull-blue/60"
                                                        >
                                                            ({{ selectedVariantDetails(item).attributes.join(', ') }})
                                                        </span>
                                                    </div>

                                                    <div v-if="packageProducts(item).length"
                                                        class="flex flex-col gap-2 border-t border-bull-blue/10 pt-3">
                                                        <p class="text-xs font-black uppercase text-bull-blue/60">
                                                            Incluye</p>
                                                        <div v-for="packageProduct in packageProducts(item)"
                                                            :key="packageProduct.id"
                                                            class="text-sm text-bull-blue/70">
                                                            <p class="font-bold text-bull-blue">
                                                                {{ packageProduct.name }}
                                                            </p>
                                                            <p>
                                                                <span class="font-bold text-bull-blue">Selección:</span>
                                                                {{ selectedVariantDetails(item, packageProduct.id).name }}
                                                                <span
                                                                    v-if="selectedVariantDetails(item, packageProduct.id).attributes.length"
                                                                    class="text-bull-blue/60"
                                                                >
                                                                    ({{ selectedVariantDetails(item, packageProduct.id).attributes.join(', ') }})
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-6">
                                        <NeoCard bg="white-neon" variant="flat" padding="p-5">
                                            <FormTitles title="Resumen" subtitle="Desglose de tu compra"
                                                :icon="Receipt" />
                                            <div class="mt-5 flex flex-col gap-3 text-sm font-bold text-bull-blue/80">
                                                <div class="flex justify-between gap-4">
                                                    <span>Subtotal</span>
                                                    <span>${{ currentOrder.subtotal }}</span>
                                                </div>
                                                <div v-if="Number(currentOrder.product_discount_amount) > 0"
                                                    class="flex justify-between gap-4 text-bull-pink">
                                                    <span>Descuento de productos</span>
                                                    <span>- ${{ currentOrder.product_discount_amount }}</span>
                                                </div>
                                                <div v-if="Number(currentOrder.coupon_discount_amount) > 0"
                                                    class="flex justify-between gap-4 text-bull-pink">
                                                    <span>
                                                        Cupón{{ currentOrder.coupon_code ? ` (${currentOrder.coupon_code})` : '' }}
                                                    </span>
                                                    <span>- ${{ currentOrder.coupon_discount_amount }}</span>
                                                </div>
                                                <div class="flex justify-between gap-4">
                                                    <span>Envío</span>
                                                    <span>${{ currentOrder.shipping_cost }}</span>
                                                </div>
                                                <div
                                                    class="flex justify-between gap-4 border-t-4 border-bull-blue pt-3 text-xl font-black text-bull-blue">
                                                    <span>Total</span>
                                                    <span>${{ currentOrder.total_amount }}</span>
                                                </div>
                                            </div>
                                        </NeoCard>

                                        <NeoCard bg="white" variant="flat" padding="p-5">
                                            <FormTitles title="Información" subtitle="Datos asociados al pedido"
                                                :icon="User" />
                                            <div class="mt-5 flex flex-col gap-3 text-sm">
                                                <p class="flex items-start gap-2 font-bold text-bull-blue">
                                                    <Mail class="mt-0.5 h-4 w-4 flex-shrink-0 text-bull-pink" />
                                                    {{ currentOrder.customer_email }}
                                                </p>
                                                <p v-if="currentOrder.shipping_address?.city"
                                                    class="flex items-start gap-2 font-bold text-bull-blue">
                                                    <MapPin class="mt-0.5 h-4 w-4 flex-shrink-0 text-bull-pink" />
                                                    {{ currentOrder.shipping_address.city }}, {{
                                                    currentOrder.shipping_address.state }}
                                                </p>
                                                <p v-if="currentOrder.is_gift"
                                                    class="flex items-start gap-2 font-bold text-bull-blue">
                                                    <Gift class="mt-0.5 h-4 w-4 flex-shrink-0 text-bull-pink" />
                                                    Es un regalo
                                                </p>
                                            </div>
                                        </NeoCard>
                                    </div>
                                </div>

                                <div v-if="currentOrder.notes || currentOrder.gift_message"
                                    class="grid gap-4 md:grid-cols-2">
                                    <NeoCard v-if="currentOrder.notes" bg="white-aqua" variant="flat" padding="p-5">
                                        <FormTitles title="Notas" :icon="Info" />
                                        <p class="mt-4 text-sm font-medium text-bull-blue">{{ currentOrder.notes }}</p>
                                    </NeoCard>
                                    <NeoCard v-if="currentOrder.gift_message" bg="pink-neon" variant="flat"
                                        padding="p-5">
                                        <FormTitles title="Mensaje de regalo" :icon="Gift" text-color="white"
                                            icon-bg-color="neon" />
                                        <p class="mt-4 text-sm font-bold text-white">{{ currentOrder.gift_message }}</p>
                                    </NeoCard>
                                </div>
                            </div>
                        </div>
                    </NeoCard>
                </div>
            </div>

            <Pagination
                v-if="props.isAuthenticated && visibleOrders.length"
                :links="pagination.links ?? []"
                :total="pagination.total ?? 0"
                :from="pagination.from ?? 0"
                :to="pagination.to ?? 0"
                :last-page="pagination.last_page ?? 1"
                type-records="pedidos"
            />

            <div v-if="guestOrder" class="flex justify-start">
                <Button variant="neo-pink" routeName="order.track" shape="pill">
                    <ArrowLeft class="h-5 w-5" />
                    Nueva búsqueda
                </Button>
            </div>
        </section>
    </MainLayout>
</template>
