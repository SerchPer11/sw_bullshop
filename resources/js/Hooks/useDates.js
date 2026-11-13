import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import 'dayjs/locale/es'; // 1. Importamos el diccionario en español

// Extendemos los plugins
dayjs.extend(utc);
dayjs.extend(timezone);

// 2. Establecemos español como idioma global
dayjs.locale('es'); 

export const formatDate = (dateString) => {
    if (!dateString) return '';

    // Convertimos de UTC a la hora local del usuario
    const date = dayjs.utc(dateString).local();

    // 3. Detectamos si trae hora (los formatos de fecha pura "YYYY-MM-DD" no traen ":")
    const hasTime = dateString.includes(':');

    if (hasTime) {
        // Formato con hora: "11 de noviembre de 2026 a las 15:30"
        return date.format('D [de] MMMM [de] YYYY [a las] HH:mm');
    }

    // Formato sin hora: "11 de noviembre de 2026"
    return date.format('D [de] MMMM [de] YYYY');
};