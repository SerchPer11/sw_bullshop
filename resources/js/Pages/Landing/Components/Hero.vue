<script setup>
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { PawPrint } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import HeroCard from './HeroCard.vue';

const props = defineProps({
    featPackageData: {
        type: Object,
        required: true
    }
});
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 py-12 lg:py-0 lg:min-h-[calc(100vh-120px)] flex flex-col justify-center">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center w-full">
      
      <!-- COLUMNA IZQUIERDA: Textos y CTA -->
      <div class="flex flex-col items-start space-y-6 lg:pr-10">
        
        <div class="flex flex-wrap gap-2 text-[10px] sm:text-xs font-bold uppercase tracking-wider">
          <Badge variant="neo-blue" shape="pill">
            ⚡ Edición Limitada 2026
          </Badge>
          <Badge variant="neo-neon" shape="pill">
            100% Hecho para braquicéfalos
          </Badge>
          <Badge variant="neo-pink" shape="pill">
            Match canino & humano
          </Badge>
        </div>

        <h1 class="text-5xl sm:text-6xl font-black uppercase leading-[0.9] tracking-tight">
          Streetwear & <br />
          Accesorios Para <br />
          <span class="inline-block bg-bull-neon px-2 border-4 border-bull-blue mt-2 mb-2">
            Bulldogs
          </span> Y Sus <br />
          Humanos.
        </h1>

        <p class="text-base sm:text-lg font-medium text-bull-blue/80 max-w-xl leading-relaxed">
          El match perfecto entre tú y tu bulldog. Piezas ergonómicas 
          diseñadas para pechos de barril y cuellos potentes, 
          confeccionadas con lona pesada, algodón premium y 
          broches metálicos indestructibles.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto pt-4">
          <Button variant="neo-pink" size="lg" class="w-full sm:w-auto text-base">
            Ver Drops & Apartar 
            <PawPrint class="w-5 h-5 ml-2" />
          </Button>
          <Button variant="neo-white" size="lg" class="w-full sm:w-auto text-base">
            Guía Anatómica Anti-Ahogo
          </Button>
        </div>

        <div class="flex items-center gap-3 pt-6 border-t-2 border-bull-blue/10 w-full">
          <div class="flex -space-x-3">
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-300"></div>
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-400"></div>
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-500"></div>
          </div>
          <div class="text-xs font-bold uppercase leading-tight">
            <span class="block text-bull-blue">Más de 500+ bulldogs y tutores en CDMX</span>
            <span class="text-bull-blue/60 font-medium">Calificación 4.9/5 en fittings presenciales</span>
          </div>
        </div>

      </div>

      <!-- COLUMNA DERECHA: Tarjeta de Producto Flotante -->
      <HeroCard :featPackageData="props.featPackageData" />

    </div>
  </section>
</template>