<script setup>

import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import NeoInput from '@/components/Common/NeoInput.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { User, Truck, ClipboardList, Info, Undo2, IdCard, Wallet } from 'lucide-vue-next';
import NeoCard from '@/components/Common/NeoCard.vue';
import { useCheckout } from '../Composables/useCheckout';

const {
    isSending,
    isLoading,
    form,
    user,
    paymentOptions,
    couponCode,
    appliedCoupon,
    couponError,
    isApplyingCoupon,
    applyCoupon,
    removeCoupon,
    getDiscountAmount,
    shippingCost,
    subtotal,
    getDiscounts,
    finalTotal,
    submitCheckout,
    cartStore,
} = useCheckout();

</script>

<template>

    <Head title="Finalizar Compra" />
    <MainLayout disableNav>
        <section class="bg-bull-blue text-bull-cream py-12 px-4 border-b-4 border-bull-blue">
            <div class="max-w-7xl mx-auto flex flex-col items-start gap-6">
                <div>
                    <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter">
                        <p class="text-sm font-bold uppercase tracking-[0.3em] text-bull-neon">Bullshop · Compra</p>
                        Finalizar<span class="text-bull-neon"> Compra</span>
                    </h1>
                    <p class="mt-4 text-bull-cream/80 font-medium max-w-xl">
                        Revisa tu pedido, ingresa tus datos y procede al pago. Asegúrate de que toda la información sea
                        correcta antes de continuar.
                    </p>
                </div>
            </div>
        </section>
        <section
            class="bg-bull-neon mx-auto w-full text-bull-blue py-4 px-4 text-xs font-bold uppercase tracking-wider flex flex-row  gap-4 items-center z-50 justify-center">
            <Info class="text-bull-blue w-10 h-10 md:w-6 md:h-6" />
            <p class="text-sm md:text-lg">Por el momento, solo se realizarán entregas en el evento...</p>
        </section>
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="flex items-center gap-4 mb-8">
                <Button variant="neo-pink" routeName="collection.index" shape="pill">
                    <Undo2 />
                    <span class="sm:hidden">Cancelar</span>
                    <span class="hidden sm:block">Cancelar</span>
                </Button>
            </div>

            <!-- Grid de 2 Columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                <!-- COLUMNA IZQUIERDA: Formularios (7 columnas) -->
                <div class="flex flex-col gap-8">

                    <!-- Sección: Contacto -->
                    <NeoCard bg="white" padding="p-6">
                        <FormTitles title="Información de contacto" subtitle="Ingresa tus datos de contacto"
                            :icon="User" />

                        <div class="flex flex-col gap-4 mt-4">
                            <NeoInput label="Correo Electrónico" id="email" v-model="form.email"
                                placeholder="juan123@gmail.com" :required="true" class="w-full" type="email"
                                :error="form.errors?.email" validation="email" />
                            <NeoInput label="Teléfono (opcional)" id="phone" v-model="form.shipping.phone"
                                placeholder="123-456-7890" class="w-full" type="tel"
                                :error="form.errors['shipping.phone']" validation="phone-mx" />

                            <!-- Opción para crear cuenta (Solo Guests) -->
                            <div v-if="!user" class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="create_account" v-model="form.create_account"
                                    class="w-5 h-5 border-2 border-bull-blue text-bull-pink rounded-none focus:ring-bull-pink">
                                <label for="create_account" class="font-bold text-bull-blue text-sm">Guardar mi
                                    información y crear cuenta</label>
                            </div>
                            <NeoInput v-if="form.create_account" v-model="form.password" type="password"
                                label="Crea una contraseña" placeholder="Crea una contraseña" class="w-full"
                                :required="true" :error="form.errors?.password" validation="password" />

                            <NeoInput label="Confirma la Contraseña" v-if="form.create_account"
                                v-model="form.password_confirmation" type="password"
                                placeholder="Confirma la contraseña" class="w-full" :required="true"
                                :error="form.errors?.password_confirmation" validation="password" />
                        </div>
                    </NeoCard>

                    <!-- Sección: Dirección de Envío -->
                    <NeoCard bg="white" padding="p-6">
                        <FormTitles title="Información para entrega"
                            subtitle="Ingresa los datos de la persona que recibirá el pedido" :icon="IdCard" />
                        <!--h2 class="text-2xl font-black uppercase text-bull-blue mb-4">Dirección de Envío</h2-->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <NeoInput label="Nombre(s)" id="name" v-model="form.shipping.name" placeholder="Juan"
                                :required="true" class="w-full" :error="form.errors['shipping.name']"
                                validation="alpha" />

                            <NeoInput label="Apellido(s)" id="lastname" v-model="form.shipping.lastname"
                                placeholder="Pérez" :required="true" class="w-full"
                                :error="form.errors['shipping.lastname']" validation="alpha" />
                            <!--input v-model="form.shipping.street" type="text" placeholder="Calle y número"
                                class="w-full border-2 border-bull-blue p-3 bg-white md:col-span-2" />
                            <input v-model="form.shipping.city" type="text" placeholder="Ciudad"
                                class="w-full border-2 border-bull-blue p-3 bg-white" />
                            <input v-model="form.shipping.zip" type="text" placeholder="Código Postal"
                                class="w-full border-2 border-bull-blue p-3 bg-white" />
                            <input v-model="form.shipping.phone" type="tel" placeholder="Teléfono"
                                class="w-full border-2 border-bull-blue p-3 bg-white md:col-span-2" /-->
                        </div>
                    </NeoCard>

                    <NeoCard bg="white" padding="p-6">
                        <FormTitles title="Método de Pago" subtitle="Elige cómo deseas liquidar tu pedido"
                            :icon="Wallet" />

                        <div class="mt-4 ">
                            <NeoInput type="radio" id="payment_method" v-model="form.payment_method"
                                :options="paymentOptions" :error="form.errors?.payment_method" required />

                            <!-- Mensaje de contexto dependiendo del método -->
                            <p v-if="form.payment_method === 1"
                                class="text-xs font-bold text-bull-blue/80 mt-4 uppercase">
                                <span class="text-xs font-bold text-bull-pink">*</span> Apartaremos tu pedido. Deberás
                                liquidarlo en efectivo el día del evento.
                            </p>
                            <p v-if="form.payment_method === 2"
                                class="text-xs font-bold text-bull-blue/80 mt-4 uppercase">
                                <span class="text-xs font-bold text-bull-pink">*</span> Serás redirigido a la pasarela
                                segura de Stripe para completar tu pago.
                            </p>
                        </div>
                    </NeoCard>

                </div>

                <!-- COLUMNA DERECHA: Resumen (5 columnas, Sticky) -->
                <NeoCard bg="white" padding="p-6" class="sticky top-24">
                    <FormTitles title="Resumen del Pedido"
                        subtitle="Revisa los detalles de tu pedido antes de finalizar" :icon="ClipboardList" />

                    <!-- Lista de Productos Pinia -->
                    <div class="flex flex-col gap-4 max-h-64 overflow-y-auto pr-2 mt-4">
                        <div v-for="item in cartStore.items" :key="item.uniqueKey"
                            class="flex items-center gap-4 border-b-2 border-bull-blue/20 pb-4">
                            <div class="h-16 w-16 border-2 border-bull-blue relative flex-shrink-0">
                                <img :src="item.image" class="h-full w-full object-cover" />
                            </div>
                            <div class="flex-grow">
                                <p class="font-black text-sm uppercase text-bull-blue line-clamp-1">{{ item.name }}</p>
                                <p class="text-xs font-bold text-bull-blue/60">{{ item.details }}</p>
                                <p class="text-sm font-bold text-bull-blue/80">Cantidad: {{ item.quantity }} </p>
                            </div>
                            <span v-if="item?.compared_price" class="font-bold text-bull-pink/50 line-through">
                                ${{ item?.compared_price * item.quantity }}
                            </span>
                            <span class="font-black text-bull-blue whitespace-nowrap">${{ item.price * item.quantity
                            }}</span>
                        </div>
                    </div>

                    <!-- Input de Cupones -->
                    <div class="flex flex-col gap-2 mb-6 pt-6 border-bull-blue/20">
                        <div v-if="!appliedCoupon" class="flex gap-2">
                            <NeoInput v-if="!appliedCoupon" label="Código de descuento" v-model="couponCode" type="text"
                                placeholder="DESCUENTO2023" class="w-full uppercase font-bold"
                                :error="form.errors?.coupon" uppercase />
                            <Button variant="neo-blue" @click="applyCoupon"
                                :disabled="isApplyingCoupon || !couponCode || isSending" class="mt-8"
                                :loading="isLoading" loadingText="Aplicando">Aplicar</Button>
                        </div>
                        <div v-else
                            class="flex justify-between items-center bg-bull-neon/20 border-2 border-bull-neon p-3">
                            <div class="flex items-center gap-2">
                                <Badge variant="neo-neon" shape="pill">{{ appliedCoupon.code }}</Badge>
                            </div>
                            <button @click="removeCoupon"
                                class="text-xs font-bold text-bull-blue hover:underline">Quitar</button>
                        </div>
                        <span v-if="couponError" class="text-red-500 text-xs font-bold">{{ couponError }}</span>
                    </div>

                    <!-- Totales -->
                    <div class="flex flex-col gap-2 font-bold text-bull-blue/90 pt-4 border-t-2 border-bull-blue/20">
                        <div class="flex justify-between items-end">
                            <span class="text-lg font-black uppercase text-bull-blue/90">Subtotal</span>
                            <span class="text-xl font-black text-bull-blue/90">${{ subtotal }}</span>
                        </div>
                        <div class="flex justify-between text-bull-pink/90">
                            <span class="uppercase">Descuento</span>
                            <span>- ${{ getDiscounts }}</span>
                        </div>
                        <!--div class="flex justify-between">
                            <span>Envío Estándar</span>
                            <span>${{ shippingCost }}</span>
                        </div-->
                        <div v-if="appliedCoupon" class="flex justify-between text-bull-pink/90">
                            <span>{{ appliedCoupon.code }}</span>
                            <span>{{ getDiscountAmount() }}</span>
                        </div>

                        <div class="flex justify-between items-end mt-4 pt-4 border-t-4 border-bull-blue">
                            <span class="text-xl font-black uppercase">Total Final</span>
                            <span class="text-3xl font-black text-bull-blue">${{ finalTotal }}</span>
                        </div>
                    </div>

                    <!-- Ejemplo en tu CheckOut.vue -->
                    <div class="flex items-start gap-2 mt-8 mb-4">
                        <input type="checkbox" id="terms" v-model="form.terms_accepted"
                            class="mt-1 form-checkbox text-bull-pink border-bull-blue focus:ring-bull-pink" required>
                        <label for="terms" class="text-xs font-bold text-bull-blue/80 leading-tight">
                            He leído y acepto los
                            <a :href="route('legal.terms')" target="_blank"
                                class="text-bull-pink hover:underline">Términos y Condiciones</a>, la
                            <a :href="route('legal.refunds')" target="_blank"
                                class="text-bull-pink hover:underline">Política de Devoluciones</a>, el
                            <a :href="route('legal.privacy')" target="_blank"
                                class="text-bull-pink hover:underline">Aviso de Privacidad</a> y la
                            <a :href="route('legal.shipping')" target="_blank"
                                class="text-bull-pink hover:underline">Política de Envíos</a>.
                        </label>
                    </div>

                    <!-- Botón de Pago -->
                    <Button variant="neo-neon" size="xl" class="w-full text-xl mt-2" @click="submitCheckout"
                        :disabled="isLoading || isSending || cartStore.items.length === 0 || !form.terms_accepted" :loading="isSending"
                        loadingText="Procesando">
                        Pagar Ahora
                    </Button>
                </NeoCard>

            </div>
        </section>
    </MainLayout>
</template>