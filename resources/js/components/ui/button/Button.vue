<script setup>
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from ".";
import { Loader2 } from "lucide-vue-next";

const props = defineProps({
  variant: { type: null, required: false },
  size: { type: String, required: false },
  shape: { type: null, required: false },
  class: { type: null, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false, default: "button" },
  loading: { type: Boolean, required: false, default: false },
});
</script>

<template>
  <Primitive
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size, shape }), props.class)"
  >
    <Loader2 v-if="loading" class="w-5 h-5 mr-2 animate-spin" />
    <slot name="icon" v-if="!loading" />
    <slot />
  </Primitive>
</template>