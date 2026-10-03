<script setup>
import { reactiveOmit } from "@vueuse/core";
import "vue-sonner/style.css";
import {
  CircleCheckIcon,
  InfoIcon,
  Loader2Icon,
  OctagonXIcon,
  TriangleAlertIcon,
  XIcon,
} from "lucide-vue-next";
import { Toaster as Sonner } from "vue-sonner";

const props = defineProps({
  id: { type: String, required: false },
  invert: { type: Boolean, required: false },
  theme: { type: String, required: false },
  position: { type: String, required: false },
  closeButtonPosition: { type: String, required: false },
  hotkey: { type: Array, required: false },
  richColors: { type: Boolean, required: false },
  expand: { type: Boolean, required: false },
  duration: { type: Number, required: false },
  gap: { type: Number, required: false },
  visibleToasts: { type: Number, required: false },
  closeButton: { type: Boolean, required: false },
  toastOptions: { type: Object, required: false },
  class: { type: String, required: false },
  style: { type: Object, required: false },
  offset: { type: [Object, String, Number], required: false },
  mobileOffset: { type: [Object, String, Number], required: false },
  dir: { type: String, required: false },
  swipeDirections: { type: Array, required: false },
  icons: { type: Object, required: false },
  containerAriaLabel: { type: String, required: false },
});
const delegatedProps = reactiveOmit(props, "toastOptions");
</script>

<template>
  <Sonner
    class="toaster group"
    
    :toast-options="{
      classes: {
        toast: 'group toast !border-4 !border-bull-blue !rounded-none !font-black !uppercase !tracking-wider !shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] !p-4 !items-center',
        
        success: '!bg-bull-neon !text-bull-blue',
        
        error: '!bg-bull-pink !text-white',
        
        title: '!text-lg !font-black',
        description: '!font-bold !opacity-90',
        icon: '!w-8 !h-8',
        
        actionButton: '!bg-bull-blue !text-bull-neon !font-bold !border-2 !border-bull-blue !rounded-none',
        cancelButton: '!bg-bull-pink !text-white !font-bold !rounded-none',
      },
    }"
    v-bind="delegatedProps"
  >
    <template #success-icon>
      <CircleCheckIcon class="size-8" />
    </template>
    <template #info-icon>
      <InfoIcon class="size-8" />
    </template>
    <template #warning-icon>
      <TriangleAlertIcon class="size-8" />
    </template>
    <template #error-icon>
      <OctagonXIcon class="size-8" />
    </template>
    <template #loading-icon>
      <div>
        <Loader2Icon class="size-4 animate-spin" />
      </div>
    </template>
    <template #close-icon>
      <XIcon class="size-4" />
    </template>
  </Sonner>
</template>
