<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import { Button } from '@/components/ui/button';
import { MailCheck, ArrowRight, LogOut } from 'lucide-vue-next';
import { useLoading } from '@/Hooks/useLoading';

const { isLoading, setLoading } = useLoading();

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const cooldownSeconds = 60;
const time = ref(0);
let timer = null;

const startCooldown = () => {
    time.value = cooldownSeconds;

    if (timer) {
        clearInterval(timer);
    }

    timer = setInterval(() => {
        time.value--;

        if (time.value <= 0) {
            clearInterval(timer);
            timer = null;
        }
    }, 1000);
};

const formattedTime = computed(() => {
    const minutes = Math.floor(time.value / 60);
    const seconds = time.value % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

const submit = () => {
    setLoading(true);

    form.post(route('verification.send'), {
        onSuccess: () => startCooldown(),
        onFinish: () => setLoading(false),
    });
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');

onMounted(() => {
    if (verificationLinkSent.value) {
        startCooldown();
    }
});

onBeforeUnmount(() => {
    if (timer) {
        clearInterval(timer);
    }
});
</script>

<template>
    <Head title="Verificar Correo" />

    <MainLayout>
        <section class="max-w-md mx-auto px-4 py-20 flex flex-col items-center justify-center">
            
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-bull-blue mb-8 text-center">
                Revisa tu <br><span class="text-bull-pink">Bandeja</span>
            </h1>

            <NeoCard bg="white" padding="p-8" class="w-full relative overflow-hidden text-center">
                
                <div class="flex justify-center mb-6 text-bull-neon">
                    <MailCheck class="w-16 h-16" stroke-width="1.5" />
                </div>

                <h2 class="text-4xl font-black uppercase text-bull-blue tracking-wide mb-4">
                    ¡Casi listos!
                </h2>

                <p class="text-bull-blue/80 font-bold leading-relaxed mb-6">
                    Gracias por registrarte en BullShop. Antes de comenzar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar. 
                </p>

                <p class="text-bull-blue/80 font-bold text-sm mb-6">
                    ¿No recibiste el correo? Revisa tu carpeta de spam o solicita uno nuevo.
                </p>

                <div v-if="verificationLinkSent" class="mb-6 font-bold text-sm text-bull-blue bg-bull-neon/10 p-3 border-2 border-bull-neon w-full">
                    Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.
                </div>

                <form @submit.prevent="submit" class="flex flex-col gap-4">
                    <Button 
                        type="submit" 
                        variant="neo-neon" 
                        size="xl" 
                        class="w-full uppercase tracking-wider text-sm md:text-base"
                        :loading="form.processing"
                        loadingText="Enviando..."
                        :disabled="isLoading || time > 0"
                        
                    >
                        {{
                            isLoading
                                ? 'Enviando...'
                                : time > 0
                                    ? `Reenviar en ${formattedTime}`
                                    : 'Reenviar correo de verificación'
                        }}
                    </Button>

                    <Button 
                        routeName="logout"
                        method="post" 
                        as="button"
                        variant="neo-pink-ghost"
                        class="w-full uppercase"
                        :disabled="form.processing || isLoading"
                    >
                        Cerrar sesión <LogOut class="w-4 h-4" /> 
                </Button>
                </form>
            </NeoCard>
        </section>
    </MainLayout>
</template>