import { createInertiaApp } from '@inertiajs/vue3';
import { QueryClient, VueQueryPlugin } from '@tanstack/vue-query';
import axios from 'axios';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';

const pinia = createPinia();
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const queryClient = new QueryClient();

axios.defaults.validateStatus = () => true;

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h('div', [h(App, props), h(Toaster)]),
        })
            .use(plugin)
            .use(pinia)
            .use(VueQueryPlugin, { queryClient })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
