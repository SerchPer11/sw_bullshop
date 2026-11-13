<script setup>
import NeoCard from '@/components/Common/NeoCard.vue';
import Separator from '@/components/Common/Separator.vue';

const props = defineProps({
    cards: {
        type: Array,
        default: () => [],
    },
});

const cards = [
    {
        title: 'Para el rey de la casa',
        content: 'Gorras, bandanas, arneses y correas con toda la actitud Bullshop (A prueba de balas). CTA “Equipar al rey” ',
    },
    {
        title: 'Para quienes no pueden dejar de hablar de su bulldog',
        content: 'Tote bags y lanyards perfectas para presumirlo a donde vayas. CTA: “Quiero mi insignia”',
    },
    {
        title: 'Para ponerle carácter a tu día a día',
        content: 'stickers que llevan el universo bulldog a lo cotidiano. CTA: explorar stickers',
    },
];

</script>

<template>
    <section class="md:max-w-3xl lg:max-w-7xl mx-auto">
        <Separator title="BullShop es..." class="md:max-w-3xl lg:max-w-7xl mx-auto"/>
        <div class="md:max-w-3xl lg:max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">
            <NeoCard v-for="card in cards" :key="card.title" class="max-w-sm mx-auto min-h-[200px]" bg="white"
                variant="interactive">
                <h3 class="text-xl font-bold mb-2">{{ card.title }}</h3>
                <p class="text-justify">{{ card.content }}</p>
            </NeoCard>
        </div>
    </section>
</template>