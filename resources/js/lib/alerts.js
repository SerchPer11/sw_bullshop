import { toast } from 'vue-sonner';

const brutalistClass = 'border-4 border-bull-blue rounded-none shadow-[6px_6px_0px_0px_rgba(0,39,49,1)] font-bold uppercase tracking-wide';

export function messageSuccess(title = "¡Operación Exitosa!", message) {
    toast.success(title, {
        description: message ?? "",
        // Color Neón para el éxito
        class: `bg-bull-neon text-bull-blue ${brutalistClass}`,
    });
}

export function error422(title = "¡Ups! Datos inválidos", message) {
    toast.error(title, {
        description: message?? "",
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

export function infoMessage(title = "Información", message) {
    toast.info(title, {
        description: message ?? "",
        class: `!bg-bull-blue !text-bull-cream !border-bull-pink ${brutalistClass}`,
    });
}