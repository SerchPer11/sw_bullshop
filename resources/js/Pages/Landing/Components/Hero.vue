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
  },
  featProductsData: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 pt-12 lg:py-0 lg:min-h-[calc(100vh-130px)] flex flex-col justify-center">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center w-full">

      <!-- COLUMNA IZQUIERDA: Textos y CTA -->
      <div class="flex flex-col items-start space-y-6 lg:pr-10">

        <div class="flex flex-wrap gap-2 text-[10px] sm:text-xs md:text-sm font-bold uppercase tracking-wider">
          <Badge variant="neo-blue" shape="pill">
            ⚡ Drop de apertura
          </Badge>
          <Badge variant="neo-neon" shape="pill">
            Somos BullShop
          </Badge>
          <Badge variant="neo-pink" shape="pill">
            Hecho con amor para bulldogs y sus humanos
          </Badge>
        </div>

        <h1 class="text-5xl sm:text-6xl font-black uppercase leading-[0.9] tracking-tight">
          <span class="inline-block bg-bull-neon px-2 border-4 border-bull-blue mt-2 mb-2">
            BULLSHOP
          </span>
          BULLDOG LOVERS <br />
          Accesorios para <br />
          <span class="inline-block bg-bull-pink text-white px-2 border-4 border-bull-blue mt-2 mb-2">
            Bulldogs
          </span> Y sus <br />
          Humanos.
        </h1>

        <p class="text-base sm:text-lg font-medium text-bull-blue/80 max-w-xl leading-relaxed">
          Nuestra primer colección ya esta disponible. Se de los primeros en conocerla y descubre
          lo que hemos preparado con mucho amor tu gordo y para ti.
        </p>
        <p>
          La marca oficial de la familia bulldog. <br />
          <span class="font-bold text-bull-blue italic">Creado para bulldogs, hecho por humanos.</span>
        </p>
        <div class="w-full lg:py-2 py-3 px-4">
        <Button variant="neo-pink" size="xl" class="w-full text-base" routeName="collection.index">
          VER COLECCIÓN
          <PawPrint class="w-5 h-5" />
        </Button>
        </div>

        <!--div class="flex items-center gap-3 pt-6 border-t-2 border-bull-blue/10 w-full">
          <div class="flex -space-x-3">
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-300"></div>
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-400"></div>
            <div class="w-10 h-10 rounded-full border-2 border-bull-blue bg-gray-500"></div>
          </div>
          <div class="text-xs font-bold uppercase leading-tight">
            <span class="block text-bull-blue">Más de 500+ bulldogs y tutores en CDMX</span>
            <span class="text-bull-blue/60 font-medium">Calificación 4.9/5 en fittings presenciales</span>
          </div>
        </div-->

      </div>

      <!-- COLUMNA DERECHA: Tarjeta de Producto Flotante -->
      <HeroCard :featPackageData="props.featPackageData" :featProductData="props.featProductsData" />

    </div>
  </section>
</template>