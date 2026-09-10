<script setup>
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import Hero from '@/Pages/Landing/Components/Hero.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    featuredPackageData: {
        type: Object,
        required: true
    }
});
</script>

<template>
  <Head title="Streetwear para Bulldogs y sus Humanos" />

  <MainLayout>
    <Hero :featPackageData="props.featuredPackageData" />
  </MainLayout>
</template>
