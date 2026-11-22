<script setup>
import { Undo2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

defineProps({
    orderNumber: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <section class="bg-bull-blue px-4 py-10 text-bull-cream">
            <div class="mx-auto flex max-w-7xl flex-col gap-3">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-bull-neon">BullShop · operación</p>
                <h1 class="text-4xl font-black uppercase tracking-tighter md:text-6xl">
                    Orden <span class="text-bull-neon">{{ orderNumber }}</span>
                </h1>
                <p class="font-medium text-bull-cream/80">Consulta y confirma esta orden durante la entrega.</p>
            </div>
        </section>

        <div class="mx-auto mt-4 flex max-w-7xl items-center gap-4 px-4">
            <Button variant="neo-blue-ghost" routeName="order.staff.index" shape="pill">
                <Undo2 />
                <span>Volver</span>
            </Button>
        </div>
    </div>
</template>
