import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, Fragment, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Toaster } from '@/components/ui/sonner';
import { error422, messageSuccess, infoMessage } from '@/lib/alerts';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const showFlashMessages = (flash) => {
    if (flash?.success) {
        setTimeout(() => messageSuccess(flash.success), 0);
    }

    if (flash?.error) {
        setTimeout(() => error422(flash.error), 0);
    }

    if (flash?.info) {
        setTimeout(() => infoMessage(flash.info), 0);
    }
};

router.on('success', (event) => {
    showFlashMessages(event.detail.page.props.flash);
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        showFlashMessages(props.initialPage?.props?.flash);

        return createApp({
            render: () => h(Fragment, [
                h(Toaster, { 
                    position: 'top-center', 
                    expand: false, 
                    duration: 5000}),
                h(App, props),
            ]),
        })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#D4E818',
    },
});
