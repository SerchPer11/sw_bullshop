<script setup>
import { Gift, Info, Mail, Phone, User } from 'lucide-vue-next';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <NeoCard bg="white" padding="p-6">
        <FormTitles title="Cliente" subtitle="Datos de la orden" :icon="User" />

        <div class="mt-5 flex flex-col gap-3 text-sm font-bold text-bull-blue">
            <p class="text-base font-black">{{ order.customer_name }}</p>
            <p class="flex items-center gap-2">
                <Mail class="h-4 w-4 shrink-0 text-bull-pink" />
                <span class="break-all">{{ order.customer_email }}</span>
            </p>
            <p v-if="order.customer_phone" class="flex items-center gap-2">
                <Phone class="h-4 w-4 shrink-0 text-bull-pink" />
                <span>{{ order.customer_phone }}</span>
            </p>
            <p v-if="order.notes" class="flex items-start gap-2 border-t border-bull-blue/10 pt-3">
                <Info class="mt-0.5 h-4 w-4 shrink-0 text-bull-pink" />
                <span>Notas: {{ order.notes }}</span>
            </p>
            <p v-if="order.is_gift || order.gift_message" class="flex items-start gap-2 border-t border-bull-blue/10 pt-3">
                <Gift class="mt-0.5 h-4 w-4 shrink-0 text-bull-pink" />
                <span>{{ order.gift_message ? `Regalo: "${order.gift_message}"` : 'Marcado como regalo' }}</span>
            </p>
        </div>
    </NeoCard>
</template>
