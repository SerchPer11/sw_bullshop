<script setup>
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import Hero from '@/Pages/Landing/Components/Hero.vue';
import { Head } from '@inertiajs/vue3';
import Manifest from '@/Pages/Landing/Components/Manifest.vue';
import Offert from '@/Pages/Landing/Components/Offert.vue';
import TopProducts from '@/Pages/Landing/Components/TopProducts.vue';
import JoinTheClub from '@/Pages/Landing/Components/JoinTheClub.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const props = defineProps({
    featuredPackageData: {
        type: Object,
        required: true
    },
    featuredProductsData: {
        type: Object,
        required: true
    }
});
</script>

<template>
  <Head title="Streetwear para Bulldogs y sus Humanos" />

  <MainLayout>
    <Hero />

    <Manifest />

    <Offert id="about-us"/>

    <TopProducts :featPackageData="props.featuredPackageData" :featProductsData="props.featuredProductsData" />

    <JoinTheClub v-if="!$page?.props?.auth?.user" />
  </MainLayout>
</template>
