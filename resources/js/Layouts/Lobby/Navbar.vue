<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import { ReceiptText, ArrowRight, LogOut, User, LogIn } from 'lucide-vue-next'; // Agregamos iconos para el usuario
import { useCartStore } from '@/./Stores/useCartStore';
import BagDrawer from '@/components/Lobby/ShoppingBag/BagDrawer.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const user = computed(() => page?.props?.auth?.user);
const roles = computed(() => page?.props?.auth?.roles);

const isMobileMenuOpen = ref(false);

const toggleMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const isActiveLink = (href) => {
  return window.location.pathname === href;
};

const cartStore = useCartStore();
</script>

<template>
  <nav class="bg-bull-blue border-b-4 border-bull-pink px-4 py-4 relative z-40">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <!-- LOGO -->
      <Link href="/" class="flex items-center gap-2">
        <div class="bg-bull-blue p-1.5 rounded-sm transition-colors duration-300">
            <img src="/Images/Logo/logo_neon.webp" alt="BullShop Logo Icon" class="h-8 w-auto object-contain" loading="eager" />
        </div>
      </Link>

      <!-- ENLACES DE NAVEGACIÓN (Centro - Desktop) -->
      <div class="hidden lg:flex items-center gap-6 font-bold text-sm uppercase tracking-wide text-bull-cream">
        <Link href="/" class="hover:text-bull-neon transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/')}">Inicio</Link>
        <Link href="/coleccion" class="hover:text-bull-neon transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/coleccion')}">Colección</Link>
        <Link v-if="roles[0] === 'admin'|| roles[0] === 'staff'" href="/staff/orders" class="hover:text-bull-neon transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/staff/orders')}">Apartados Evento</Link>
        <Link v-if="roles[0] === 'admin'|| roles[0] === 'staff'" href="/admin/pos" class="hover:text-bull-neon transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/admin/pos')}">POS Pop-Up</Link>
        <Link href="/lobby#about-us" class="hover:text-bull-neon transition-colors duration-300" :class="{'text-bull-neon bg-bull-blue transition-colors duration-300 p-2 rounded shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] shadow-bull-pink': isActiveLink('/about-us')}">Sobre nosotros</Link>
      </div>

      <!-- BOTONES DE ACCIÓN (Derecha - Desktop) -->
      <div class="hidden lg:flex items-center gap-4">
        <BagDrawer />
        
        <!-- VISTA PARA INVITADOS (NO LOGUEADOS) -->
        <template v-if="!$page?.props?.auth?.user">
          <Button variant="neo-aqua" size="default" shape="pill" routeName="order.track">
            <ReceiptText /> Rastrear pedido
          </Button>
          
          <Button v-if="!isActiveLink('/login')" variant="neo-neon-ghost" size="default" shape="pill" routeName="login" title="Iniciar Sesión">
            Iniciar Sesión
          </Button>
          
          <Button v-if="!isActiveLink('/register')" variant="neo-pink" size="lg" routeName="register" title="Unete al club">  
            Unete al club
            <ArrowRight />
          </Button>
        </template>

        <!-- VISTA PARA USUARIOS AUTENTICADOS -->
        <template v-else>
          <Button routeName="order.my-orders" variant="neo-aqua" size="default" shape="pill" title="Ver mis compras">
            <ReceiptText /> Mis compras
          </Button>
          <div class="flex items-center gap-2">
          <Button variant="neo-neon-ghost" size="default" shape="pill" showable :icon="User" :title="'Ver perfil de ' + $page?.props?.auth?.user.name.split(' ')[0]">
            {{ $page?.props?.auth?.user.name.split(' ')[0] }}
          </Button>

          <!-- Importante: el logout en Breeze DEBE ser method="post" y as="button" -->
          <Button routeName="logout" method="post" as="button" variant="neo-pink" size="icon" shape="pill" title="Cerrar Sesión" :icon="LogOut" showable showableSide="right">
            Cerrar Sesión 
          </Button>
          </div>
        </template>
      </div>

      <!-- MENÚ HAMBURGUESA (Móvil) -->
      <button @click="toggleMenu" class="lg:hidden p-2 text-bull-neon focus:outline-none" title="Menú">
        <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

    </div>

    <!-- MENÚ DESPLEGABLE MÓVIL (Simple) -->
    <div v-show="isMobileMenuOpen" class="lg:hidden absolute top-full left-0 w-full bg-bull-blue border-b-4 border-bull-pink flex flex-col p-4 gap-4 shadow-lg text-bull-cream">
      <Link href="/" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-pink pb-1">Inicio</Link>
      <Link href="/coleccion" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-pink pb-1">Colección</Link>
      <!---Este sera el link para ver los apartado del evento para staff-->
      <Link v-if="roles[0] === 'admin' || roles[0] === 'staff'" href="/staff/orders" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-pink pb-1">Apartados Evento</Link>
      <Link v-if="roles[0] === 'admin' || roles[0] === 'staff'" href="/admin/pos" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-pink pb-1">POS Pop-Up</Link>
      <Link href="/lobby#about-us" class="font-bold uppercase border-b-2 border-transparent hover:border-bull-pink pb-1">Sobre nosotros</Link>
      
      <div class="flex flex-col gap-2 mt-4 pt-4 border-t-2 border-bull-pink border-dashed">
        <BagDrawer :isMobileMenuOpen="isMobileMenuOpen" />
        
        <!-- VISTA PARA INVITADOS MÓVIL -->
        <template v-if="!$page?.props?.auth?.user">
          <Button routeName="order.track" variant="neo-aqua" class="w-full">
            <ReceiptText /> Rastrear pedido
          </Button>
          <Button v-if="!isActiveLink('/login')" routeName="login" variant="neo-blue" class="w-full">
            Iniciar Sesión
            <LogIn />
          </Button>
          <Button v-if="!isActiveLink('/register')" variant="neo-pink" class="w-full" routeName="register">
            Unete al club <ArrowRight />
          </Button>
        </template>

        <!-- VISTA PARA USUARIOS MÓVIL -->
        <template v-else>
          <Button routeName="order.my-orders" variant="neo-aqua" class="w-full" shape="pill">
            <ReceiptText /> Mis compras
          </Button>
          <div class="grid grid-cols-2 gap-2">
          <Button  variant="neo-neon-ghost" class="w-full" shape="pill">
            <User />
            {{ $page?.props.auth.user.name.split(' ')[0] }} {{ $page?.props.auth.user.lastname.split(' ')[0] }}
          </Button>
          <Button routeName="logout" method="post" as="button" variant="neo-pink" class="w-" shape="pill" title="Cerrar Sesión">
            Cerrar Sesión
            <LogOut class="w-4 h-4" />
          </Button>
          </div>
        </template>
      </div>
    </div>
  </nav>
</template>