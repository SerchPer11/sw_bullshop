<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'static', // Opciones: 'static', 'interactive', 'flat'
    },
    //  bg-white, bg-[#e6ff00], bg-pink-500
    bg: {
        type: String,
        default: 'white',
    },
    // Controla el padding interno, útil para quitarlo en tarjetas de productos con fotos al borde
    padding: {
        type: String,
        default: 'p-6',
    }
});

const variantClasses = computed(() => {
    // Bordes súper gruesos
    const base = 'border-4 border-bull-blue rounded-none transition-all duration-200';
    
    const variants = {
        // static: La tarjeta clásica de BullShop con sombra dura desplazada.
        static: `${base} shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]`,
        
        // interactive: Al hacer hover, se "hunde" como un botón mecánico pesado.
        interactive: `${base} shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[6px] hover:translate-y-[6px] cursor-pointer`,
        
        // flat: Solo el borde pesado sin la sombra exterior. Útil para anidar tarjetas dentro de otras.
        flat: `${base}`,
    };

    return variants[props.variant] || variants.static;
});

const bg = computed(() => {
    const base = 'bg-white';

    const backgrounds = {
        'white': `${base}`,
        'blue': 'bg-bull-blue',
        'blue-pink': 'bg-bull-blue border-bull-pink',
        'pink': 'bg-bull-pink',
        'aqua': 'bg-bull-aqua',
        'neon': 'bg-bull-neon',
        'cream': 'bg-bull-cream',
        'transparent': 'bg-transparent',
        'white-pink': 'bg-white border-bull-pink',
        'white-blue': 'bg-white border-bull-blue',
        'white-aqua': 'bg-white border-bull-aqua',
        'white-neon': 'bg-white border-bull-neon',
        'transparent-pink': 'bg-transparent border-bull-pink',
        'transparent-blue': 'bg-transparent border-bull-blue',
        'transparent-aqua': 'bg-transparent border-bull-aqua',
        'transparent-neon': 'bg-transparent border-bull-neon',
        'pink-neon': 'bg-bull-pink border-bull-neon',
    };

    return backgrounds[props.bg] || base;
});
</script>

<template>
    <div :class="[variantClasses, bg, padding]">
        <slot></slot>
    </div>
</template>