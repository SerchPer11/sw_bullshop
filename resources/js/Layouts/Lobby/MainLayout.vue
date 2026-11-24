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

    <div v-if="!$page.props.store.online_sales_enabled"
      class="bg-bull-pink text-white border-b-4 border-[#021a24] px-4 py-2.5 text-center text-xs sm:text-sm font-black uppercase tracking-wide">
      ¡Gracias por hacer historia en el Reto Guinness! Estamos preparando envíos a todo México para Navidad. Muy
      pronto podrás comprar en línea.
    </div>

    <Navbar v-if="!props.disableNav" />

    <main class="flex-grow">
      <slot />
    </main>

    <Footer />
  </div>
</template>