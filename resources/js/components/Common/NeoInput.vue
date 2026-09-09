<script setup>
import { computed, ref } from 'vue';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Switch } from '@/Components/ui/switch';
import { Checkbox } from '@/Components/ui/checkbox';
import { Eye, EyeOff } from 'lucide-vue-next'; // Usando Lucide como acordamos

const props = defineProps({
    modelValue: [String, Number, Boolean, null],
    type: { type: String, default: 'text' },
    label: String,
    error: String,
    placeholder: String,
    required: { type: Boolean, default: false },
    options: { type: [Array, Object], default: () => [] },
    disabled: { type: Boolean, default: false },
    // --- NUEVA PROP PARA VALIDACIÓN ---
    rule: { type: String, default: 'none' }, // 'numeric', 'alpha', 'alphanumeric'
});

const emit = defineEmits(['update:modelValue']);
const showPassword = ref(false);

const computedInputType = computed(() => {
    if (props.type === 'password') return showPassword.value ? 'text' : 'password';
    return props.type;
});

// --- LÓGICA DE VALIDACIÓN FRONTAL ---
const handleInput = (event) => {
    let value = event.target.value;
    
    // Filtramos lo que el usuario teclea en tiempo real
    if (props.rule === 'numeric') {
        value = value.replace(/[^0-9]/g, ''); // Solo números
    } else if (props.rule === 'alpha') {
        value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''); // Solo letras y espacios
    } else if (props.rule === 'alphanumeric') {
        value = value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, ''); // Letras y números
    }
    
    // Si es tipo email, no bloqueamos el tecleo (porque el usuario necesita escribir @ y .), 
    // la validación real de email se hace al enviar el form o con el atributo type="email".

    // Actualizamos el valor real
    emit('update:modelValue', value);
    // Forzamos la actualización visual en el input
    event.target.value = value;
};
</script>

<template>
    <div class="space-y-2">
        <Label v-if="!['switch', 'checkbox'].includes(type) && label" :for="label"
            class="text-sm font-bold uppercase tracking-wider text-bull-blue"
            :class="{ 'text-bull-pink': error }">
            {{ label }} <span v-if="required" class="text-bull-pink">*</span>
        </Label>

        <!-- INPUT ESTÁNDAR (Con diseño Neo-Brutalista) -->
        <div v-if="['text', 'email', 'password', 'number'].includes(type)" class="relative w-full">
            <Input :id="label" :type="computedInputType" :value="modelValue" @input="handleInput"
                :placeholder="placeholder" :disabled="disabled"
                class="border-2 border-bull-blue rounded-none focus-visible:ring-bull-neon focus-visible:ring-offset-2 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]"
                :class="{ 'border-bull-pink focus-visible:ring-bull-pink': error }" />
            
            <button v-if="type === 'password'" type="button" @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-3 flex items-center text-bull-blue hover:text-bull-pink">
                <EyeOff v-if="showPassword" class="w-5 h-5" />
                <Eye v-else class="w-5 h-5" />
            </button>
        </div>

        <!-- SELECT -->
        <Select v-else-if="type === 'select'" :model-value="modelValue" @update:modelValue="val => emit('update:modelValue', val)" :disabled="disabled">
            <SelectTrigger :id="label" class="border-2 border-bull-blue rounded-none shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]" :class="{ 'border-bull-pink': error }">
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent class="border-2 border-bull-blue rounded-none">
                <SelectItem v-for="opt in options" :key="opt.id || opt" :value="opt.id?.toString() || opt.toString()" class="font-bold uppercase focus:bg-bull-neon focus:text-bull-blue">
                    {{ opt.name || opt }}
                </SelectItem>
            </SelectContent>
        </Select>

        <!-- ERROR TEXT -->
        <p v-if="error" class="text-xs font-bold text-bull-pink uppercase tracking-wide mt-1">
            ⚠️ {{ error }}
        </p>
    </div>
</template>