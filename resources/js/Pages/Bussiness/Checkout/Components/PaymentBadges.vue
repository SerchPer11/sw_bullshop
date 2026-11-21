<script setup>
import { Lock } from 'lucide-vue-next';

const methods = [
  { name: 'Visa', src: '/Images/Payments/visa.svg' },
  { name: 'Mastercard', src: '/Images/Payments/mastercard.svg' },
  { name: 'American Express', src: '/Images/Payments/amex.svg' },
  { name: 'Apple Pay', src: '/Images/Payments/apple-pay.svg' },
  { name: 'Google Pay', src: '/Images/Payments/google-pay.svg' },
];
</script>

<template>
  <div class="mt-3 flex flex-col items-center gap-2">
    <div class="flex flex-wrap items-center justify-center gap-1.5">
      <div
        v-for="method in methods"
        :key="method.name"
        class="flex h-7 w-11 items-center justify-center rounded border border-bull-blue/20 bg-white shadow-sm"
        :title="method.name"
      >
        <img
          :src="method.src"
          :alt="method.name"
          class="max-h-full max-w-full object-contain"
          loading="lazy"
        />
      </div>
    </div>

    <p class="flex items-center gap-1.5 text-xs text-bull-blue/70">
      <Lock class="h-3.5 w-3.5 shrink-0" />
      <span>Pago 100% seguro y encriptado procesado por <strong>Stripe</strong>.</span>
    </p>
  </div>
</template>