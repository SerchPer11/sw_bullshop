<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/Lobby/MainLayout.vue';
import NeoCard from '@/components/Common/NeoCard.vue';
import { ShieldCheck, FileText, RotateCcw, Truck, Mail } from 'lucide-vue-next';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        required: true,
    },
    badge: {
        type: String,
        default: ' Centro Legal',
    },
    updatedAt: {
        type: String,
        default: 'Noviembre 2026',
    },
    currentRoute: {
        type: String,
        default: '',
    },
});

const legalLinks = [
    {
        name: 'Aviso de Privacidad',
        routeName: 'legal.privacy',
        path: '/legal/aviso-de-privacidad',
        icon: ShieldCheck,
    },
    {
        name: 'Términos y Condiciones',
        routeName: 'legal.terms',
        path: '/legal/terminos-y-condiciones',
        icon: FileText,
    },
    {
        name: 'Devoluciones y Reembolsos',
        routeName: 'legal.refunds',
        path: '/legal/devoluciones',
        icon: RotateCcw,
    },
    {
        name: 'Política de Envíos',
        routeName: 'legal.shipping',
        path: '/legal/envios',
        icon: Truck,
    },
];

const isCurrentPolicy = (item) => {
    if (props.currentRoute) {
        return props.currentRoute === item.routeName;
    }
    return typeof window !== 'undefined' && window.location.pathname === item.path;
};
</script>

<template>
    <MainLayout>
        <Head :title="`${title} | BullShop`" />

        <!-- CABECERA BRUTALISTA DE MARCA -->
        <section class="bg-bull-blue text-bull-cream py-12 px-4 border-b-4 border-bull-pink">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <p class="text-xs sm:text-sm font-bold uppercase tracking-[0.3em] text-bull-neon mb-2">
                        BullShop · {{ badge }}
                    </p>
                    <h1 class="text-3xl sm:text-5xl md:text-6xl font-black uppercase tracking-tighter leading-none">
                        {{ title }}
                    </h1>
                </div>

                <div class="bg-bull-neon text-bull-blue font-bold uppercase text-xs px-4 py-2 border-2 border-bull-cream shadow-[4px_4px_0px_0px_rgba(253,231,221,1)] shrink-0">
                    Última actualización: {{ updatedAt }}
                </div>
            </div>
        </section>

        <!-- CONTENEDOR PRINCIPAL: MENÚ STICKY + DOCUMENTO LEGAL -->
        <section class="max-w-7xl mx-auto px-4 py-10 md:py-16">
            <div class="flex flex-col lg:flex-row items-start gap-8 lg:gap-10">

                <!-- MENÚ LATERAL / SUPERIOR (STICKY) CON <Link> DE INERTIA -->
                <aside class="w-full lg:w-72 shrink-0 sticky top-4 z-20">
                    <NeoCard bg="white" padding="p-4">
                        <h2 class="text-xs font-black uppercase tracking-widest text-bull-blue border-b-2 border-bull-blue pb-3 mb-3">
                            Documentos Legales
                        </h2>

                        <nav class="flex lg:flex-col gap-2 overflow-x-auto pb-1 lg:pb-0">
                            <Link
                                v-for="item in legalLinks"
                                :key="item.routeName"
                                :href="route(item.routeName)"
                                class="flex items-center gap-2.5 px-3.5 py-2.5 text-xs sm:text-sm font-bold uppercase tracking-wide border-2 transition-all shrink-0 lg:shrink"
                                :class="
                                    isCurrentPolicy(item)
                                        ? 'bg-bull-blue text-bull-neon border-bull-blue shadow-[4px_4px_0px_0px_rgba(242,17,109,1)]'
                                        : 'bg-white text-bull-blue border-bull-blue/20 hover:border-bull-blue hover:bg-bull-cream/50'
                                "
                            >
                                <component :is="item.icon" class="w-4 h-4 shrink-0" />
                                <span>{{ item.name }}</span>
                            </Link>
                        </nav>

                        <!-- Bloque de soporte rápido para cumplimiento Stripe -->
                        <div class="hidden lg:block mt-6 pt-4 border-t-2 border-dashed border-bull-blue/20 text-xs text-slate-600">
                            <p class="font-bold text-bull-blue uppercase mb-1 flex items-center gap-1.5">
                                <Mail class="w-3.5 h-3.5 text-bull-pink" />
                                ¿Dudas legales o aclaraciones?
                            </p>
                            <a
                                href="mailto:hola@bullshop.mx"
                                class="font-bold text-bull-pink hover:underline break-all"
                            >
                                hola@bullshop.mx
                            </a>
                        </div>
                    </NeoCard>
                </aside>

                <!-- CONTENEDOR DEL TEXTO LEGAL (ALTA LEGIBILIDAD, MAX-W-4XL) -->
                <div class="w-full max-w-4xl flex-1">
                    <NeoCard bg="white" padding="p-6 sm:p-10 md:p-12">
                        <!--
                            Opción A: Renderiza el contenido enviado por prop `content` desde LegalController.
                            Opción B: Si prefieres escribir contenido directo en slot, también soporta <slot />.
                        -->
                        <article
                            class="legal-content text-slate-800 text-base leading-relaxed space-y-4"
                            v-html="content"
                        />
                    </NeoCard>
                </div>

            </div>
        </section>
    </MainLayout>
</template>

<style scoped>
/* Tipografía limpia y altamente legible para el documento legal */
.legal-content :deep(h2) {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 800;
    color: rgb(5, 76, 104); /* bull-blue */
    text-transform: uppercase;
    letter-spacing: 0.025em;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(5, 76, 104, 0.12);
}

.legal-content :deep(h2:first-child) {
    margin-top: 0;
}

.legal-content :deep(p) {
    margin-bottom: 1rem;
    color: #1e293b; /* slate-800 */
    line-height: 1.75;
}

.legal-content :deep(ul),
.legal-content :deep(ol) {
    margin-top: 0.5rem;
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
    color: #1e293b;
}

.legal-content :deep(ul) {
    list-style-type: disc;
}

.legal-content :deep(ol) {
    list-style-type: decimal;
}

.legal-content :deep(li) {
    margin-bottom: 0.5rem;
    line-height: 1.65;
}

.legal-content :deep(a) {
    color: rgb(242, 17, 109); /* bull-pink */
    font-weight: 700;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.legal-content :deep(strong) {
    color: #0f172a; /* slate-900 */
    font-weight: 700;
}
</style>
