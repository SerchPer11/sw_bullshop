<script setup>
import { cn } from "@/lib/utils";
import { badgeVariants } from ".";

const props = defineProps({
  variant: { type: null, required: false },
  shape: { type: null, required: false },
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});
</script>

<template>
  <div :class="cn(badgeVariants({ variant, shape }), props.class)">
    <slot />
  </div>
</template>
