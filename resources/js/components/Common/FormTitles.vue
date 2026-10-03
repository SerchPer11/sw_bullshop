<script setup>
import BaseIcon from './BaseIcon.vue';
import { computed } from 'vue';

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, required: false },
  icon: { type: [String, Object, Function], required: false },
  type: { type: String, required: false },
});

const isStringIcon = computed(() => typeof props.icon === 'string');
</script>

<template>
  <div class="flex gap-2 items-center">
    <BaseIcon v-if="icon && isStringIcon" class="bg-bull-blue text-white rounded-lg" :path="icon" size="24"
      h="h-10" w="w-10" />

    <div v-else-if="icon && !isStringIcon"
      class="bg-bull-neon text-bull-blue rounded-lg h-10 w-10 flex items-center justify-center border-2 border-bull-blue p-2 shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
      <component :is="icon" :size="24" />
    </div>

    <div class="space-y-0 text-bull-blue ml-1">
      <p class="text-2xl font-bold uppercase">
        {{ title }}
      </p>
      <p v-if="subtitle" class="text-sm font-semibold text-bull-blue/80 tracking-wide">
        {{ subtitle }}
      </p>
    </div>
  </div>
</template>