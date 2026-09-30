<script setup>
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from ".";
import { Loader2 } from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
  variant: { type: null, required: false },
  size: { type: String, required: false },
  shape: { type: null, required: false },
  class: { type: null, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false, default: "button" },
  disabled: { type: Boolean, required: false, default: false },
  loading: { type: Boolean, required: false, default: false },
  loadingText: { type: String, required: false, default: "Procesando..." },
});

const isDisabled = computed(() => props.disabled || props.loading);
</script>

<template>
  <Primitive
    :as="as"
    :as-child="asChild"
    :disabled="isDisabled"
    :class="cn(buttonVariants({ variant, size, shape }), props.class)"
  >
    <Loader2 v-if="loading" class="w-5 h-5 mr-2 animate-spin" />
    <template v-if="loading">
      {{ loadingText }}
    </template>
    <template v-else>
      <slot name="icon" v-if="!loading" />
      <slot />
      <slot name="icon" v-if="!loading" />
    </template>
  </Primitive>
</template>