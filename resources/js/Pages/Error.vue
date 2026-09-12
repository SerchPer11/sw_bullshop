<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Home, AlertTriangle } from 'lucide-vue-next';

const props = defineProps({
  status: Number,
});

const title = computed(() => {
  return {
    503: 'Servicio no disponible',
    500: 'Error del servidor',
    404: 'Página no encontrada',
    403: 'Acceso denegado',
  }[props.status] || 'Error desconocido';
});

const description = computed(() => {
  return {
    503: 'Estamos ajustando collares. Vuelve en unos minutos.',
    500: 'Ups, el servidor mordió un cable. Ya lo estamos arreglando.',
    404: 'Parece que tu bulldog se comió esta página y la enterró en el patio.',
    403: 'Hey, no tienes permiso para entrar a esta zona.',
  }[props.status] || 'Algo salió mal, pero no eres tú, somos nosotros.';
});
</script>

<template>
  <Head :title="title" />
  
  <div class="min-h-screen bg-bull-cream flex flex-col items-center justify-center p-4 text-center">

    <div class="bg-bull-neon border-4 border-bull-blue shadow-[12px_12px_0px_0px_rgba(0,39,49,1)] p-8 md:p-16 max-w-2xl w-full flex flex-col items-center">
      
      <AlertTriangle class="w-20 h-20 text-bull-blue mb-6" />
      
      <h1 class="text-8xl font-black text-bull-pink tracking-tighter mb-2">
        {{ status }}
      </h1>
      
      <h2 class="text-3xl font-bold uppercase text-bull-blue mb-4 border-b-4 border-bull-blue pb-4 w-full">
        {{ title }}
      </h2>
      
      <p class="text-lg font-medium text-bull-blue/80 mb-8 max-w-md">
        {{ description }}
      </p>

      <Button :as="Link" href="/" variant="neo-blue" size="xl" class="w-full sm:w-auto text-lg">
        <Home class="w-6 h-6 mr-2" />
        Regresar al Inicio
      </Button>
      
    </div>
  </div>
</template>