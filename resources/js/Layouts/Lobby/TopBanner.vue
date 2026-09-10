<script setup>
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';

// Aquí luego podemos meter la lógica del temporizador real
const timeLeft = ref('47:59:19');
</script>

<template>
  <div class="bg-bull-pink">
    <div class="max-w-7xl mx-auto w-full  text-white  py-1.5 px-4 text-xs font-bold uppercase tracking-wider flex flex-col sm:flex-row justify-between items-center z-50">
    <!-- Mensaje principal -->
    <div class="flex items-center gap-2 text-center sm:text-left mb-1 sm:mb-0">
      <span>PIEZAS DE EDICIÓN LIMITADA 2026 // NO HABRÁ RESTOCK</span>
      
      <!-- Badge de cupos (oculto en pantallas ultra pequeñas por espacio) -->
      <Badge class="hidden md:inline-block" variant="neo-neon" shape="rounded">
        CUPOS 40/50
      </Badge>
    </div>

    <!-- Contador -->
    <div class="flex items-center gap-2">
      <span class="hidden sm:inline">CIERRE DROP:</span>
      <Badge variant="neo-blue" shape="rounded">
        {{ timeLeft }}
      </Badge>
    </div>
    </div>
  </div>
</template>