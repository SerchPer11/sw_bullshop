<script setup>
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { PawPrint } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import HeroCard from './HeroCard.vue';

</script>

<template>
  <section class="max-w-7xl mx-auto px-4 py-12 xl:py-2 xl:min-h-[calc(100vh-130px)] flex flex-col xl:justify-center">

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

      </div>

      <!-- COLUMNA DERECHA: Tarjeta de Producto Flotante -->
      <HeroCard />

    </div>
  </section>
</template>