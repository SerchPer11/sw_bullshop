<script setup>
import { computed } from 'vue';

const { featPackageData } = defineProps({
    featPackageData: {
        type: Object,
        required: true
    }
});

const featPackage = featPackageData.data;

const discountPercentage = computed(() => {
    if (!featPackage.price || featPackage.compare_at_price <= featPackage.price) {
        return null;
    }
    const discount = ((featPackage.compare_at_price - featPackage.price) / featPackage.compare_at_price) * 100;
    return Math.round(discount);
});

const stockStatus = computed(() => {
    if (featPackage.stock > 0) {
        return featPackage.stock <= 5 ? `🔥 Solo ${featPackage.stock} en stock` : `COMPARTE EL ESTILO CON TU COMPAÑERO`;
    } else {
        return 'Sold Out';
    }
});

</script>

<template>
    <div class="relative w-full max-w-md mx-auto lg:max-w-[360px] xl:max-w-[400px] flex justify-center lg:justify-end mt-10 lg:mt-0">
        
        <div class="relative bg-bull-neon border-4 border-bull-blue shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] p-4 rounded-xl w-full aspect-[4/5]">
          
          <div :class="['absolute -top-2 -left-4 z-10 font-bold text-sm uppercase px-3 py-1.5 border-2 border-bull-blue shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] rotate-[-4deg]',
            featPackage.stock > 0 
            && featPackage.stock <= 5
                ? 'bg-bull-pink text-white'
                : featPackage.is_new && featPackage.stock > 0 ? 'bg-bull-aqua text-white'
                : 'bg-bull-blue text-bull-neon']
            ">
            <span v-if="featPackage.stock > 0" class="text-xs">
               {{ stockStatus }}
            </span>
            <span v-else class="text-xs">
              Sold Out
            </span>
          </div>

          <div class="w-full h-full bg-bull-pink/20 border-2 border-bull-blue/20 rounded-lg overflow-hidden relative">
            <img 
              src="/Images/Lobby/img_hero1.webp" 
              alt="Bulldog usando Streetwear BullShop" 
              class="w-full h-full object-cover object-center"
              loading="eager"
            />
            <div v-if="discountPercentage" class="absolute top-3 right-3 bg-bull-blue text-bull-neon font-black text-sm px-2 py-1 border-2 border-bull-neon/50 shadow-[2px_2px_0px_0px_rgba(0,39,49,1)]">
                -{{ discountPercentage }}% OFF
            </div>
          </div>

          <div class="absolute -bottom-6 right-4 left-4 bg-white border-4 border-bull-blue p-3 flex justify-between items-center shadow-[4px_4px_0px_0px_rgba(0,39,49,1)]">
            <div class="flex flex-col">
              <span class="font-black text-sm uppercase">{{ featPackage.name  }}</span>
              <span class="text-[10px] text-bull-blue/70 font-bold uppercase max-w-[220px] pb-1">{{ featPackage.description }}</span>
            </div>
            <div class="flex flex-col items-end">
              <span class="text-bull-pink font-black text-lg">$3,850 MXN</span>
              <span v-if="featPackage.compare_at_price" class="text-sm font-bold line-through opacity-70">
                ${{ featPackage.compare_at_price }} MXN
              </span>
            </div>
          </div>

          <div class="absolute -bottom-10 left-0 z-10 bg-bull-pink text-white font-bold text-xs uppercase px-3 py-1.5 border-2 border-bull-blue shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] rotate-[2deg]">
            ® Patrón Morfológico Registrado
          </div>

        </div>
      </div>
</template>