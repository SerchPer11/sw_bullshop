<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import NeoInput from '@/components/Common/NeoInput.vue'; // <-- Tu joya importada
import { Button } from '@/components/ui/button';
import { LogIn } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión" />

    <MainLayout>
        <section class="max-w-md mx-auto px-4 py-20 flex flex-col items-center justify-center">
            
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-bull-blue mb-8 text-center">
                Bienvenido a <br><span class="text-bull-pink">BullShop</span>
            </h1>

            <div v-if="status" class="mb-4 font-bold text-sm text-green-600 bg-green-100 p-3 border-2 border-green-600 w-full text-center">
                {{ status }}
            </div>

            <NeoCard bg="white" padding="p-8" class="w-full relative overflow-hidden">
                <FormTitles 
                    title="Iniciar Sesión" 
                    subtitle="Accede a tu cuenta y tus pedidos" 
                    :icon="LogIn" 
                />

                <form @submit.prevent="submit" class="mt-8 flex flex-col gap-6">
                    
                    <NeoInput 
                        id="email" 
                        type="email" 
                        label="Correo Electrónico" 
                        placeholder="ejemplo@correo.com"
                        v-model="form.email" 
                        :error="form.errors.email" 
                        :required="true"
                    />

                    <NeoInput 
                        id="password" 
                        type="password" 
                        label="Contraseña" 
                        v-model="form.password" 
                        :error="form.errors.password" 
                        :required="true"
                    />

                    <div class="flex items-center justify-between mt-2">
                        <NeoInput 
                            id="remember" 
                            type="checkbox" 
                            label="Recordarme" 
                            v-model="form.remember" 
                        />

                        <Link 
                            v-if="canResetPassword" 
                            :href="route('password.request')" 
                            class="font-bold text-sm text-bull-pink hover:underline"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>

                    <Button 
                        type="submit" 
                        variant="neo-blue" 
                        size="xl" 
                        class="w-full uppercase tracking-wider mt-4"
                        :loading="form.processing"
                    >
                        Entrar a mi cuenta
                    </Button>

                    <div class="text-center mt-4">
                        <span class="font-bold text-bull-blue/70 text-sm">¿No tienes cuenta? </span>
                        <Link :href="route('register')" class="font-black text-bull-blue hover:text-bull-pink transition-colors uppercase text-sm">
                            Regístrate aquí
                        </Link>
                    </div>
                </form>
            </NeoCard>
        </section>
    </MainLayout>
</template>