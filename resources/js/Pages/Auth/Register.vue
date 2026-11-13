<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import FormTitles from '@/components/Common/FormTitles.vue';
import NeoInput from '@/components/Common/NeoInput.vue';
import { Button } from '@/components/ui/button';
import { UserPlus, ArrowRight } from 'lucide-vue-next';

const form = useForm({
    name: '',
    'lastname': '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Crear Cuenta" />

    <MainLayout>
        <section class="max-w-2xl mx-auto px-4 py-20 flex flex-col items-center justify-center">

            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-bull-blue mb-8 text-center">
                Únete al <br><span class="text-bull-pink">Club</span>
            </h1>

            <NeoCard bg="white" padding="p-8" class="w-full relative overflow-hidden">
                <FormTitles title="Registro" subtitle="Crea tu cuenta para gestionar tus compras" :icon="UserPlus" />
                <form @submit.prevent="submit" class="mt-8 flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <NeoInput id="name" type="text" label="Nombre(s)" placeholder="Juan" v-model="form.name"
                            :error="form.errors.name" :required="true" />

                        <NeoInput id="lastname" type="text" label="Apellido(s)" placeholder="Lopez"
                            v-model="form.lastname" :error="form.errors.lastname" :required="true" />
                    </div>
                    <NeoInput id="email" type="email" label="Correo Electrónico" placeholder="ejemplo@correo.com"
                        v-model="form.email" :error="form.errors.email" :required="true" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <NeoInput id="password" type="password" label="Contraseña" v-model="form.password"
                            :error="form.errors.password" :required="true" />

                        <NeoInput id="password_confirmation" type="password" label="Confirmar Contraseña"
                            v-model="form.password_confirmation" :error="form.errors.password_confirmation"
                            :required="true" />
                    </div>
                    <Button type="submit" variant="neo-neon" size="xl" class="w-full uppercase tracking-wider mt-4 text-xl"
                        :loading="form.processing">
                        Crear Cuenta
                        <ArrowRight />
                    </Button>

                    <div class="text-center mt-4">
                        <span class="font-bold text-bull-blue/70 text-sm">¿Ya tienes cuenta? </span>
                        <Link :href="route('login')"
                            class="font-black text-bull-blue hover:text-bull-pink transition-colors uppercase text-sm">
                            Inicia Sesión aquí
                        </Link>
                    </div>
                </form>
            </NeoCard>
        </section>
    </MainLayout>
</template>