<script setup>
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from ".";
import { Loader2 } from "lucide-vue-next";
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  variant: { type: null, required: false },
  size: { type: String, required: false },
  shape: { type: null, required: false },
  class: { type: null, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: [String, Object], required: false, default: "button" },
  disabled: { type: Boolean, required: false, default: false },
  loading: { type: Boolean, required: false, default: false },
  loadingText: { type: String, required: false, default: "Procesando..." },
  //para manejo de rutas dinámicas con Ziggy
  href: { type: String, required: false, default: null },
  routeName: { type: String, required: false, default: null },
  parameter: { type: [Number, Object, String], required: false, default: null },
  target: { type: String, required: false, default: null },
  type: { type: String, required: false, default: null },
  showable: { type: Boolean, required: false, default: false },
  persistent: { type: [String, Number], required: false, default: null },
  icon: { type: [Object, Function], required: false, default: null },
  showableSide: {
    type: String,
    required: false,
    default: "left",
    validator: (value) => ["left", "right"].includes(value)
  }
});

const isDisabled = computed(() => props.disabled || props.loading);

// Determina el elemento a renderizar (Link de Inertia, <a> o el definido en 'as')
const computedAs = computed(() => {
  if (props.asChild) return "template";
  if (isDisabled.value) return "button";
  if (props.routeName) return Link;
  if (props.href) return "a";
  return props.as;
});

// Genera la URL dinámica usando Ziggy si routeName existe, o toma el href directo
const computedHref = computed(() => {
  if (props.routeName) {
    return route(props.routeName, props.parameter ?? undefined);
  }
  return props.href;
});

// Solo asigna type="button/submit" si el elemento final es realmente un botón
const computedType = computed(() => {
  if (computedAs.value === "button" || props.as === "button") {
    return props.type ?? "button";
  }
  return null;
});
</script>

<template>
  <Primitive
    :as="computedAs"
    :as-child="asChild"
    :disabled="isDisabled"
    :href="computedHref"
    :target="target"
    :type="computedType"
    :class="cn(
      buttonVariants({ variant, size, shape }),
      props.class,
      {
        'group lg:w-fit lg:px-3 lg:transition-[width,padding] lg:duration-300 lg:hover:px-5': showable,
        'lg:gap-2': showable && persistent !== null,
        'lg:gap-0 lg:group-hover:gap-2': showable && persistent === null,
        'opacity-50 cursor-not-allowed': loading
      }
    )"
  >
    <Loader2 v-if="loading" class="w-5 h-5 mr-2 animate-spin" />
    <template v-if="loading">
      {{ loadingText }}
    </template>
    <template v-else>
      <template v-if="showable">
        <component
          v-if="icon"
          :is="icon"
          class="h-5 w-5 shrink-0"
          :class="{ 'order-2': showableSide === 'right' }"
        />
        <span v-else class="shrink-0" :class="{ 'order-2': showableSide === 'right' }">
          <slot name="icon" />
        </span>
        <span
          class="overflow-hidden whitespace-nowrap lg:max-w-0 lg:opacity-0 lg:transition-[max-width,opacity,margin] lg:duration-300 lg:group-hover:max-w-96 lg:group-hover:opacity-100"
          :class="{
            'order-1': showableSide === 'right',
            'lg:group-hover:ml-2': persistent === null && showableSide === 'left',
            'lg:group-hover:mr-2': persistent === null && showableSide === 'right'
          }"
        >
          <slot />
        </span>
        <span
          v-if="persistent !== null"
          class="shrink-0"
          :class="{ 'order-1': showableSide === 'right' }"
        >
          {{ persistent }}
        </span>
      </template>
      <template v-else>
        <component v-if="icon" :is="icon" class="h-5 w-5" />
        <slot v-else name="icon" />
        <slot />
        <slot v-if="!icon" name="icon" />
      </template>
    </template>
  </Primitive>
</template>