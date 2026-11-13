<script setup>
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';

const props = defineProps({
    // Aquí podemos recibir props si es necesario
});

const texts = [
  "Tener un bulldog va más allá de tener un perro: es compartir la vida con un ser que parece entenderlo todo.",
  "Es enamorarse de esa cara expresiva,  de esa rebeldía única para salirse siempre con la suya y de la facilidad con la que se adueña de tu cama, tiempo y corazón",
  "Bullshop entiende lo que significa, y lo importante que es llevarlo cerquita de ti."
]

// Aquí luego podemos meter la lógica del temporizador real
const timeLeft = ref('47:59:19');
</script>

<template>
  <section class="bg-bull-pink">
    <div
      class="max-w-7xl mx-auto mb-6 w-full text-white py-4 px-4 text-xs font-bold uppercase tracking-wider flex flex-row justify-between items-center z-50">
      
      <div class="flex animate-marquee-infinite min-w-full shrink-0 items-center justify-around gap-12 pr-12">
        <div v-for="(text, index) in texts" :key="'orig-' + index" class="flex items-center gap-2 shrink-0">
          <span>{{ text }}</span>
          <span class="text-white/40 font-black">•</span>
        </div>
      </div>
      <div class="flex animate-marquee-infinite min-w-full shrink-0 items-center justify-around gap-12 pr-12aria-hidden='true'">
        <div v-for="(text, index) in texts" :key="'clon-' + index" class="flex items-center gap-2 shrink-0">
          <span>{{ text }}</span>
          <span class="text-white/40 font-black">•</span>
        </div>
      </div>

    </div>
  </section>
      <!-- Contador 
      <div class="flex items-center gap-2">
        <span class="hidden sm:inline">CIERRE DROP:</span>
        <Badge variant="neo-blue" shape="rounded">
          {{ timeLeft }}
        </Badge>
      </div>-->
</template>