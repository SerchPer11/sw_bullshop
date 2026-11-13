<script setup>
import BaseIcon from './BaseIcon.vue';
import { computed } from 'vue';

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, required: false },
  icon: { type: [String, Object, Function], required: false },
  type: { type: String, required: false },
  textColor: { type: String, required: false, default: 'blue' },
  iconBgColor: { type: String, required: false, default: 'neon' },
  iconTextColor: { type: String, required: false, default: 'blue' },
});

const bgColors = computed(() => ({
  'blue': 'bg-bull-blue',
  'neon': 'bg-bull-neon',
  'cream': 'bg-bull-cream',
  'white': 'bg-white',
  'pink': 'bg-bull-pink',
  'aqua': 'bg-bull-aqua',
}));

const textColors = computed(() => ({
  'blue': 'text-bull-blue',
  'neon': 'text-bull-neon',
  'cream': 'text-bull-cream',
  'white': 'text-white',
  'pink': 'text-bull-pink',
  'aqua': 'text-bull-aqua',
}));

const resolveColor = (value, colors) => colors[value] ?? value;

const resolvedTextColor = computed(() => resolveColor(props.textColor, textColors.value));
const resolvedIconBgColor = computed(() => resolveColor(props.iconBgColor, bgColors.value));
const resolvedIconTextColor = computed(() => resolveColor(props.iconTextColor, textColors.value));
const isStringIcon = computed(() => typeof props.icon === 'string');
</script>

<template>
  <div class="flex gap-2 items-center">
    <BaseIcon v-if="icon && isStringIcon" class="rounded-lg" :class="[resolvedIconTextColor, resolvedIconBgColor]" :path="icon" size="24"
      h="h-10" w="w-10" />

    <div v-else-if="icon && !isStringIcon"
      :class="[resolvedIconBgColor, resolvedIconTextColor]"
      class="rounded-lg h-10 w-10 flex items-center justify-center border-2 border-bull-blue p-2 shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
      <component :is="icon" :size="24" />
    </div>

    <div :class="resolvedTextColor" class="space-y-0 ml-1">
      <p class="text-2xl font-bold uppercase">
        {{ title }}
      </p>
      <p v-if="subtitle" class="text-sm font-semibold opacity-80 tracking-wide">
        {{ subtitle }}
      </p>
    </div>
  </div>
</template>