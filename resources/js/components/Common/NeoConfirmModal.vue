<script setup>
import { computed } from 'vue';
import {
  AlertDialog,
  AlertDialogContent,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogCancel,
  AlertDialogTrigger
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';

const isOpen = defineModel('open', { type: Boolean, default: false });

const props = defineProps({
  title: { type: String, default: '¿Estás absolutamente seguro?' },
  description: { type: String, default: 'Esta acción no se puede deshacer.' },
  confirmText: { type: String, default: 'Sí, continuar' },
  cancelText: { type: String, default: 'Cancelar' },
  variant: { type: String, default: 'danger' },
  loading: { type: Boolean, default: false },
});

const emit = defineEmits(['confirm']);

const confirmBtnClass = computed(() => {
    switch (props.variant) {
        case 'danger': 
            return 'neo-pink'; // Rosa intenso para eliminar
        case 'success': 
            return 'neo-neon'; // Verde neón para acciones positivas
        case 'info': 
        default:
            return 'neo-blue'; // Azul oscuro para acciones normales
    }
});
</script>

<template>
  <!-- Controla el estado abierto/cerrado internamente o mediante v-model si es necesario,
       pero Shadcn lo maneja automático con el Trigger -->
  <AlertDialog v-model:open="isOpen">
    
    <!-- SLOT: Aquí inyectarás el botón que TÚ quieras usar para abrir el modal -->
    <AlertDialogTrigger as-child>
      <slot name="trigger" />
    </AlertDialogTrigger>
    
    <!-- Contenido Brutalista -->
    <AlertDialogContent class="border-4 border-bull-blue rounded-none shadow-[12px_12px_0px_0px_rgba(0,39,49,1)] bg-bull-cream">
      
      <AlertDialogHeader>
        <AlertDialogTitle class="text-2xl font-black uppercase text-bull-blue">
          {{ title }}
        </AlertDialogTitle>
        <AlertDialogDescription class="font-bold text-bull-blue/80">
          {{ description }}
        </AlertDialogDescription>
      </AlertDialogHeader>
      
      <AlertDialogFooter class="gap-4 sm:gap-2 mt-4">
        <AlertDialogCancel class="border-2 border-bull-blue rounded-none font-bold uppercase hover:bg-bull-blue/10 text-bull-blue m-0">
          {{ cancelText }}
        </AlertDialogCancel>
        
        <Button 
          :variant="confirmBtnClass" 
          :loading="loading"
          @click="emit('confirm')"
          class="w-full sm:w-auto"
        >
          {{ confirmText }}
        </Button>
      </AlertDialogFooter>

    </AlertDialogContent>
  </AlertDialog>
</template>