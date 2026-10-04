<script setup>
import { ref, watch, computed } from 'vue';
import NeoInput from '@/components/Common/NeoInput.vue';
import { Trash2, Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const props = defineProps({
    question: {
        type: Object,
        required: true
    },
    // En el repeater, el modelValue será un Array de Objetos, no un String
    modelValue: {
        type: Array,
        default: () => []
    },
    loading: {
        type: Boolean,
        default: false
    },
    plusButtonText: {
        type: String,
        default: 'Agregar otro'
    },
    error: {
        type: Object,
        default: () => []
    },
    errorPrefix: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const subFields = computed(() => {
    if (Array.isArray(props.question.options)) return props.question.options;

    if (typeof props.question.options === 'string') {
        try {
            const parsedOptions = JSON.parse(props.question.options);
            return Array.isArray(parsedOptions) ? parsedOptions : [];
        } catch {
            return [];
        }
    }

    return [];
});

// Usamos una copia reactiva local para iterar
const items = ref([...props.modelValue]);

const fieldError = (index, field) => {
    if (items.value.length === 0) return null;

    const errorKey = `${props.errorPrefix}.${index}.${field}`;
    const flatError = props.error?.[errorKey];
    const nestedError = props.error?.[props.errorPrefix]?.[index]?.[field];

    return flatError ?? nestedError ?? null;
};


// Observamos los cambios en nuestro array local de forma profunda (deep) 
// y emitimos el nuevo arreglo al formulario padre (Inertia useForm)
watch(items, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true });

// Agrega un nuevo bloque de bulldog vacío
const addItem = () => {
    const newItem = {};
    
    // Leemos el JSON de las opciones para armar el objeto vacío
    // Ejemplo: options = [{ field: 'nombre' }, { field: 'talla' }]
    // Resultado: newItem = { nombre: '', talla: '' }
    if (subFields.value.length > 0) {
        subFields.value.forEach(subField => {
            newItem[subField.field] = '';
        });
    }
    
    items.value.push(newItem);
};

// Elimina un bulldog específico (bloqueamos si solo queda uno)
const removeItem = (index) => {
    if (items.value.length > 0) {
        items.value.splice(index, 1);
    }
};
</script>

<template>
    <div class="space-y-6">
        <!-- Renderizamos cada tarjeta de "Bulldog" -->
        <div 
            v-for="(item, index) in items" 
            :key="index" 
            class="p-5 border-2 border-bull-blue bg-white shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] relative transition-all"
        >
            <!-- Cabecera de la tarjeta con el botón de eliminar -->
            <div class="flex justify-between items-center mb-4 border-b-2 border-bull-blue pb-2">
                <h4 class="font-bold uppercase tracking-wider text-bull-blue">
                    Registro #{{ index + 1 }}
                </h4>
                
                <Button 
                    v-if="items.length > 0" 
                    type="button" 
                    @click="removeItem(index)" 
                    title="Eliminar"
                    variant="neo-pink-ghost"
                    :loading="loading"
                >
                    <Trash2 class="w-4 h-4" /> Eliminar
            </Button>
            </div>

            <!-- Iteramos sobre la configuración JSON para imprimir los NeoInputs -->
            <div class="space-y-4">
                <NeoInput
                    v-for="subField in subFields"
                    :key="subField.field"
                    :type="subField.type"
                    :formatter="subField.formatter || ''"
                    :id="`repeater-${question.id}-${index}-${subField.field}`"
                    :label="subField.label"
                    :required="true"
                    :options="subField.choices || []"
                    v-model="item[subField.field]"
                    :error="fieldError(index, subField.field)"
                    :disabled="loading"
                    :input-attrs="subField.inputAttrs || {}"
                />
            </div>
        </div>

        <!-- Botón para agregar más (Estilo Neo-brutalista interactivo) -->
        <Button 
            type="button" 
            @click="addItem" 
            class="w-full"
            variant="neo-neon"
            size="lg"
            :loading="loading"

        >
            <Plus class="w-5 h-5" /> {{ plusButtonText }}
        </Button>

           <p v-if="items.length > 0 && error?.[errorPrefix]" class="text-xs font-bold text-bull-pink uppercase tracking-wide mt-1">
               {{ error[errorPrefix] }}
        </p>
    </div>
</template>