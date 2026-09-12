import { toast } from 'vue-sonner';

const brutalistClass = 'border-4 border-bull-blue rounded-none shadow-[6px_6px_0px_0px_rgba(0,39,49,1)] font-bold uppercase tracking-wide';

export function messageSuccess(message = "La operación se realizó con éxito.") {
    toast.success("¡Operación Exitosa!", {
        description: message,
        // Color Neón para el éxito
        class: `bg-bull-neon text-bull-blue ${brutalistClass}`,
    });
}

export function error422(message = "Al parecer hay campos inválidos, por favor revísalos con cuidado.") {
    toast.error("¡Ups! Datos inválidos", {
        description: message,
        // Rosa intenso para errores de usuario
        class: `bg-bull-pink text-white ${brutalistClass}`,
    });
}

export function error500(message = "Ocurrió un error inesperado, inténtalo más tarde.") {
    toast.error("Error Crítico", {
        description: message,
        // Rojo oscuro para errores de servidor
        class: `bg-bull-blue text-white border-bull-pink ${brutalistClass}`,
    });
}