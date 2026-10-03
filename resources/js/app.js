import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, Fragment, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Toaster } from '@/components/ui/sonner';
import { error500, messageSuccess } from '@/lib/alerts';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

router.on('success', (event) => {
    const flash = event.detail.page.props.flash;

    if (flash?.success) {
        setTimeout(() => messageSuccess(flash.success), 0);
    }

    if (flash?.error) {
        setTimeout(() => error500(flash.error), 0);
    }

});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () => h(Fragment, [
                h(Toaster, { 
                    position: 'top-center', 
                    expand: true, 
                    duration: 5000}),
                h(App, props),
            ]),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
