<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';


const isMobileMenuOpen = ref(false);

const toggleMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const isActiveLink = (href) => {
  return window.location.pathname === href;
};
</script>

<template>
  <nav class="bg-bull-cream border-b-4 border-bull-blue px-4 py-4 relative z-40">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <!-- LOGO -->
      <Link href="/" class="flex items-center gap-2">
        <!-- Contenedor del logo -->
        <div class="bg-bull-blue p-1.5 rounded-sm hover:bg-bull-pink">
            <img src="Images/Logo/logo_neon.webp" alt="BullShop Logo Icon" class="h-8 w-auto object-contain" loading="eager" />
        </div>
      </Link>

      <!-- ENLACES DE NAVEGACIÓN (Centro - Desktop) -->
      <div class="hidden lg:flex items-center gap-6 font-bold text-sm uppercase tracking-wide">
        <Link href="/" class="hover:text-bull-pink transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/')}">Inicio</Link>
        <Link href="/coleccion" class="hover:text-bull-pink transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/coleccion')}">Colección</Link>
        <Link href="/apartados" class="hover:text-bull-pink transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/apartados')}">Apartados Evento <span class="bg-bull-neon px-1 text-[10px] ml-1 border border-bull-blue text-bull-blue">POP-UP</span></Link>
        <Link href="/anatomia" class="hover:text-bull-pink transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/anatomia')}">Anatomía Bulldog</Link>
        <Link href="/shop" class="hover:text-bull-pink transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/shop')}">Tienda Online</Link>
      </div>

      <!-- BOTONES DE ACCIÓN (Derecha - Desktop) -->
      <div class="hidden lg:flex items-center gap-4">
        <Button variant="neo-aqua" size="default" shape="pill">
          Dog Show Ready
        </Button>
        <Button variant="neo-pink" size="lg">
          Apartar Pieza <span aria-hidden="true">&rarr;</span>
        </Button>
      </div>

      <!-- MENÚ HAMBURGUESA (Móvil) -->
      <button @click="toggleMenu" class="lg:hidden p-2 text-bull-blue focus:outline-none">
        <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

    </div>

    <!-- MENÚ DESPLEGABLE MÓVIL (Simple) -->
    <div v-show="isMobileMenuOpen" class="lg:hidden absolute top-full left-0 w-full bg-bull-cream border-b-4 border-bull-blue flex flex-col p-4 gap-4 shadow-lg">
      <Link href="/coleccion" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-blue pb-1">Colección</Link>
      <Link href="/apartados" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-blue pb-1">Apartados Evento</Link>
      <Link href="/tienda" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-blue pb-1">Tienda Online</Link>
      <div class="flex flex-col gap-2 mt-4 pt-4 border-t-2 border-bull-blue border-dashed">
        <Button variant="neo-aqua" class="w-full">Dog Show Ready</Button>
        <Button variant="neo-pink" class="w-full">Apartar Pieza &rarr;</Button>
      </div>
    </div>
  </nav>
</template>