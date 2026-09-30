<script setup>
import { useNeoInput } from '@/Hooks/useNeoInput';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Checkbox } from '@/components/ui/checkbox';
import { Eye, EyeOff } from 'lucide-vue-next';

const props = defineProps({
    modelValue: [String, Number, Boolean, Array, Object, null],
    type: { type: String, default: 'text' },
    id: { type: String, default: '' },
    label: String,
    error: [String, Array, Object, null],
    placeholder: { type: String, default: '' },
    required: { type: Boolean, default: false },
    options: { type: [Array, Object, String], default: () => [] },
    disabled: { type: Boolean, default: false },
    validation: { type: [String, Object], default: '' },
    formatter: { type: String, default: '' },
    maxSelections: { type: Number, default: null },
    inputAttrs: { type: Object, default: () => ({}) },
    rule: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue', 'validity-change']);
const { computedInputAttrs, computedInputType, displayValue, dismissError, errorMessage, fieldId,
    handleInput, handleSelectUpdate, handleSwitchUpdate, isCheckboxChecked, normalizedOptions,
    optionValue, optionKey, optionLabel, showPassword, toggleCheckbox, validate, visibleError } = useNeoInput(props, emit);
</script>

<template>
    <div class="space-y-2">
        <Label v-if="!['switch', 'checkbox', 'radio'].includes(type) && label" :for="fieldId"
            class="text-sm font-bold uppercase tracking-wider text-bull-blue" :class="{ 'text-bull-pink': visibleError }">
            {{ label }} <span v-if="required" class="text-bull-pink">*</span>
        </Label>

        <div v-if="['text', 'email', 'password', 'number', 'date', 'tel'].includes(type)" class="relative w-full">
            <Input v-bind="computedInputAttrs" :id="fieldId" :type="computedInputType" :model-value="displayValue" @focus="dismissError" @blur="validate" @update:modelValue="handleInput"
                :placeholder="placeholder" :disabled="disabled"
                class="border-2 border-bull-blue rounded-none focus-visible:ring-bull-neon focus-visible:ring-offset-2 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]"
                :class="{ 'border-bull-pink focus-visible:ring-bull-pink': visibleError }" />

            <button v-if="type === 'password'" type="button" @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-3 flex items-center text-bull-blue hover:text-bull-pink">
                <EyeOff v-if="showPassword" class="w-5 h-5" />
                <Eye v-else class="w-5 h-5" />
            </button>
        </div>

        <!-- TEXTAREA -->
        <div v-else-if="type === 'textarea'" class="relative w-full">
            <Textarea v-bind="inputAttrs" :id="fieldId" :model-value="displayValue" @focus="dismissError" @blur="validate" @update:modelValue="handleInput" :placeholder="placeholder"
                :disabled="disabled"
                class="border-2 border-bull-blue rounded-none focus-visible:ring-bull-neon focus-visible:ring-offset-2 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]"
                :class="{ 'border-bull-pink focus-visible:ring-bull-pink': visibleError }" />
        </div>

        <!-- SWITCH -->
        <div v-else-if="type === 'switch'" class="flex items-center space-x-2">
            <Switch :id="fieldId" :model-value="modelValue" @focus="dismissError" @blur="validate" @update:modelValue="handleSwitchUpdate"
                :disabled="disabled" />
        </div>

        <!-- CHECKBOX -->
        <div v-else-if="type === 'checkbox'" class="space-y-2">
            <Label v-if="label" class="text-sm font-bold uppercase tracking-wider text-bull-blue" :class="{ 'text-bull-pink': visibleError }">
                {{ label }} <span v-if="required" class="text-bull-pink">*</span>
            </Label>
            <p v-if="placeholder" class="text-xs text-bull-blue/80 tracking-wide mb-1">
                {{ placeholder }}
            </p>

            <template v-if="normalizedOptions.length">
                <div v-for="(opt, index) in normalizedOptions" :key="optionValue(opt)" class="flex items-center space-x-2">
                    <Checkbox
                        :id="`${fieldId}-${index}`"
                        :model-value="isCheckboxChecked(opt)"
                        @update:modelValue="checked => toggleCheckbox(opt, checked)"
                        @blur="validate"
                        :disabled="disabled"
                        class="w-4 h-4 text-bull-blue border-bull-blue focus:ring-bull-neon focus:ring-2 focus:ring-offset-0"
                    />
                        <Label :for="`${fieldId}-${index}`" class="text-sm font-bold uppercase tracking-wider text-bull-blue" :class="{ 'text-bull-pink': visibleError }">
                        {{ optionLabel(opt) }}
                    </Label>
                </div>
            </template>

            <div v-else class="flex items-center space-x-2">
                <Checkbox
                    :id="fieldId"
                    :model-value="modelValue === true"
                    @update:modelValue="checked => toggleCheckbox(null, checked)"
                    @blur="validate"
                    :disabled="disabled"
                />
            </div>
        </div>

        <!-- RADIO -->
        <div v-else-if="type === 'radio'" class="space-y-2">
            <Label v-if="label" class="text-sm font-bold uppercase tracking-wider text-bull-blue" :class="{ 'text-bull-pink': visibleError }">
                {{ label }} <span v-if="required" class="text-bull-pink">*</span>
            </Label>
            <div v-for="(opt, index) in normalizedOptions" :key="optionKey(opt)" class="flex items-center space-x-2">
                <input type="radio" :id="`${fieldId}-${index}`" :value="optionValue(opt)" :checked="modelValue === optionValue(opt)"
                    @focus="dismissError"
                    @blur="validate"
                    @change="() => { dismissError(); emit('update:modelValue', optionValue(opt)); }"
                    :disabled="disabled"
                    class="w-4 h-4 text-bull-blue border-bull-blue focus:ring-bull-neon focus:ring-2 focus:ring-offset-0">
                <Label :for="`${fieldId}-${index}`" class="text-sm font-bold uppercase tracking-wider text-bull-blue" :class="{ 'text-bull-pink': visibleError }">
                    {{ optionLabel(opt) }}
                </Label>
            </div>
        </div>

        <!-- SELECT -->
        <Select v-else-if="type === 'select'" :model-value="modelValue === null || modelValue === undefined ? undefined : String(modelValue)"
            @update:modelValue="handleSelectUpdate" :disabled="disabled">
            <SelectTrigger :id="fieldId"
                class="border-2 border-bull-blue rounded-none shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]"
                :class="{ 'border-bull-pink': visibleError }">
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent class="border-2 border-bull-blue rounded-none">
                <SelectItem v-for="opt in normalizedOptions" :key="optionKey(opt)" :value="optionKey(opt)"
                    class="font-bold uppercase focus:bg-bull-neon focus:text-bull-blue">
                    {{ optionLabel(opt) }}
                </SelectItem>
            </SelectContent>
        </Select>

        <!-- ERROR TEXT -->
        <p v-if="visibleError" class="text-xs font-bold text-bull-pink uppercase tracking-wide mt-1">
            {{ errorMessage }}
        </p>
    </div>
</template>