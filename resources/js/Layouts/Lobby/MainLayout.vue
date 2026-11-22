<script setup>
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';
const props = defineProps({
    disableNav: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
  <div class="min-h-screen bg-bull-cream text-bull-blue font-sans flex flex-col overflow-x-hidden">
    
    <Navbar v-if="!props.disableNav" />

    <main class="flex-grow">
      <slot />
    </main>

    <Footer />
  </div>
</template>