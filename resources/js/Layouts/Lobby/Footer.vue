<script setup>
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Instagram, Music, MessageCircle } from 'lucide-vue-next'; 
</script>

<template>
  <footer class="bg-bull-cream border-t-4 border-bull-blue pt-12 pb-6 px-4">
    <div class="max-w-7xl mx-auto">
      
      <!-- Grid Principal del Footer -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8 mb-12">
        
        <!-- Columna 1: Marca y Manifiesto -->
        <div class="flex flex-col items-start gap-4">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <div class="bg-bull-blue p-1 rounded-sm">
                <img src="Images/Logo/logo_neon.webp" alt="BullShop Logo Icon" class="h-6 w-auto object-contain" loading="lazy" />
            </div>
            <span class="font-black text-xl uppercase tracking-widest text-bull-blue">BullShop</span>
          </div>
          
          <p class="text-sm font-medium text-bull-blue/80 max-w-xs leading-relaxed">
            Marca independiente de streetwear y equipamiento ergonómico pensada exclusivamente para bulldogs y sus dueños. Hecho con orgullo en Ciudad de México.
          </p>

          <!-- Badges -->
          <div class="flex gap-2 text-[10px] font-bold uppercase tracking-wider mt-2">
            <Badge variant="neo-neon" shape="square">
              No Animal Cruelty
            </Badge>
            <Badge variant="neo-pink" shape="square">
              100% Mex Design
            </Badge>
          </div>
        </div>

        <!-- Columna 2: Navegación -->
        <div class="flex flex-col gap-4 md:pl-10">
          <h4 class="font-bold text-sm uppercase tracking-widest text-bull-blue">Explorar</h4>
          <nav class="flex flex-col gap-2 text-sm font-medium text-bull-blue/70">
            <Link href="/drops" class="hover:text-bull-pink hover:translate-x-1 transition-transform w-fit">Drops Activos</Link>
            <Link href="/apartados" class="hover:text-bull-pink hover:translate-x-1 transition-transform w-fit">Apartados Pop-Up CDMX</Link>
            <Link href="/guia" class="hover:text-bull-pink hover:translate-x-1 transition-transform w-fit">Guía de Tallas y Cuidado</Link>
            <Link href="/vip" class="hover:text-bull-pink hover:translate-x-1 transition-transform w-fit">Membresía VIP</Link>
          </nav>
        </div>

        <!-- Columna 3: Contacto y Redes -->
        <div class="flex flex-col gap-4">
          <h4 class="font-bold text-sm uppercase tracking-widest text-bull-blue">Contacto & Atelier</h4>
          <p class="text-sm font-medium text-bull-blue/70">
            ¿Dudas con la talla de tu can o pedidos a medida?
          </p>
          <a href="mailto:hola@bullshop.mx" class="font-bold text-bull-pink hover:underline underline-offset-4 text-sm w-fit">
            hola@bullshop.mx
          </a>

          <!-- Iconos Sociales usando nuestro componente Button -->
          <div class="flex gap-2 mt-2">
            <Button variant="outline" size="icon" as="a" href="https://instagram.com" target="_blank" class="border-2 border-bull-blue text-bull-blue hover:bg-bull-blue hover:text-bull-neon">
              <Instagram class="w-4 h-4" />
            </Button>
            <Button variant="outline" size="icon" as="a" href="https://tiktok.com" target="_blank" class="border-2 border-bull-blue text-bull-blue hover:bg-bull-blue hover:text-bull-neon">
              <span class="font-black text-xs">TK</span> <!-- A veces es mejor poner texto si no hay un buen icono de TikTok -->
            </Button>
            <Button variant="outline" size="icon" as="a" href="https://whatsapp.com" target="_blank" class="border-2 border-bull-blue text-bull-blue hover:bg-bull-blue hover:text-bull-neon">
              <span class="font-black text-xs">WA</span>
            </Button>
          </div>
        </div>

      </div>

      <!-- Barra Inferior -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t-2 border-bull-blue/10 text-[10px] sm:text-xs font-bold uppercase tracking-wider text-bull-blue/60">
        <p>&copy; 2026 BullShop Indie Brand. Todos los derechos reservados.</p>
        <p class="text-bull-blue font-black">Diseñado para la forma Bulldog.</p>
      </div>

    </div>
  </footer>
</template>