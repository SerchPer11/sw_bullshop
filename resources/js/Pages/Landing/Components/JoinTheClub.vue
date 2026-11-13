<script setup>
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import { HeartHandshake, ArrowRight } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
    <section class="md:max-w-3xl lg:max-w-7xl mx-auto mb-12 max-w-sm">
        <NeoCard bg="transparent">
            <FormTitles 
                title="Únete al Club" 
                subtitle="¡Disfruta de beneficios exclusivos!" 
                :icon="HeartHandshake"
                iconBgColor="blue"
                iconTextColor="white"
                textColor="blue"

                />

            <Button routeName="register" size="xl" variant="neo-neon" class="w-full mt-6">
                <span class="mr-2 text-sm md:text-xl">¡Quiero ser parte del Club!</span>
                <ArrowRight />
            </Button>
        </NeoCard>

    </section>
</template>