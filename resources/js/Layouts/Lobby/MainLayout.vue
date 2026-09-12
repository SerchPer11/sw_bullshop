<script setup>
import TopBanner from './TopBanner.vue';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
  <div class="min-h-screen bg-bull-cream text-bull-blue font-sans flex flex-col overflow-x-hidden">
    
    <TopBanner />
    
    <Navbar />

    <main class="flex-grow">
      <slot />
      <Toaster position="bottom-right" rich-colors expand />
    </main>

    <Footer />
  </div>
</template>