<script setup>
import { cn } from "@/lib/utils";
import { skeletonVariants } from ".";

const props = defineProps({
  class: { type: String, required: false },
  variant: { type: String, required: false },
  shape: { type: String, required: false },
});
</script>

<template>
  <div :class="cn(skeletonVariants({ variant, shape }), props.class)" />
</template>
