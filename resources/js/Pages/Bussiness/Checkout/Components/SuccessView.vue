<script setup>
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/useCartStore'; // Ajusta tu ruta si es necesario
import { useCheckoutinfoStore } from '@/Stores/useCheckoutinfo';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { Button } from '@/components/ui/button';
import { CheckCircle, CalendarDays, Receipt, ArrowRight } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { formatDate } from '@/Hooks/useDates';

const page = usePage();

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    order: {
        type: Object,
        required: true,
    },
    orderTrackingUrl: {
        type: String,
        required: true,
    },
});

const order = props.order.data;
const cartStore = useCartStore();
const checkoutInfoStore = useCheckoutinfoStore();


onMounted(() => {
    cartStore.clearCart();
    checkoutInfoStore.clearCheckoutInfo();
});
</script>

<template>
    <Head :title="props.title" />
    
    <MainLayout disableNav>
        <section class="max-w-3xl mx-auto px-4 py-20 flex flex-col items-center justify-center text-center">
            
            <div class="mb-8">
                <CheckCircle class="w-24 h-24 text-bull-neon" />
            </div>

            <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter text-bull-blue mb-4">
                ¡Pedido <span class="text-bull-neon">Confirmado!</span>
            </h1>
            
            <p class="text-lg font-semibold text-bull-blue/80 mb-8 max-w-xl">
                ¡Gracias por tu compra, <span class="font-black underline">{{ order?.customer_name }}</span>! <br>Hemos recibido tu solicitud de apartado correctamente.
            </p>

            <NeoCard bg="white" padding="p-8" class="w-full text-left relative overflow-hidden">

                <FormTitles 
                    title="Detalles de tu Orden" 
                    :subtitle="`Orden #${order?.order_number}`" 
                    :icon="Receipt" 
                />

                <div class="mt-6 flex flex-col gap-4">
                    <div class="flex justify-between items-center border-b-2 border-bull-blue/10 pb-4 flex-wrap gap-2">
                        <span class="font-bold text-bull-blue uppercase text-sm">Fecha</span>
                        <span class="font-black text-bull-blue">{{ formatDate(order?.order_date?.raw) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b-2 border-bull-blue/10 pb-4">
                        <span class="font-bold text-bull-blue uppercase text-sm">Total a Pagar</span>
                        <span class="font-black text-bull-pink text-xl">${{ order?.total_amount  }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4">
                        <span class="font-bold text-bull-blue uppercase text-sm">Método de Pago</span>
                        <span class="font-black text-bull-blue flex items-center gap-2">
                            <span class="w-3 h-3 bg-bull-neon rounded-full border-2 border-bull-blue"></span>
                            {{ order?.payment_method === 1 ? 'Efectivo' : 'Tarjeta' }}
                        </span>
                    </div>
                </div>

                <NeoCard v-if="order.payment_method === 1" class="mt-6 flex gap-4 items-start" padding="p-4" variant="flat" bg="pink-neon">
                    <CalendarDays class="w-6 h-6 text-white flex-shrink-0 my-auto" />
                    <div>
                        <h4 class="font-black uppercase text-white text-sm">Importante</h4>
                        <p class="text-xs font-bold text-white mt-1">
                            Tu pedido está apartado. Recuerda que deberás liquidar el total en efectivo el día del evento al recoger tus productos.
                        </p>
                    </div>
                </NeoCard>
            </NeoCard>

            <div class="mt-12 flex gap-4 w-full md:w-auto flex-col md:flex-row">
                <Link v-if="$page.props.auth.user" :href="route('order.my-orders')" class="w-full">
                    <Button variant="neo-neon" size="xl" class="w-full uppercase tracking-wider flex gap-2">
                        Ver mis compras <ArrowRight class="w-5 h-5" />
                    </Button>
                </Link>
                <Link v-else :href="props.orderTrackingUrl" class="w-full">
                    <Button variant="neo-neon" size="xl" class="w-full uppercase tracking-wider flex gap-2">
                        Ver mi pedido <ArrowRight class="w-5 h-5" />
                    </Button>
                </Link>
                <Link :href="route('collection.index')" class="w-full">
                    <Button variant="neo-blue" size="xl" class="w-full uppercase tracking-wider flex gap-2">
                        Seguir Explorando <ArrowRight class="w-5 h-5" />
                    </Button>
                </Link>
            </div>

        </section>
    </MainLayout>
</template>