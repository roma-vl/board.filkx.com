import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, watch } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { createI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import messages from '@/lang';
import CanDirective from '@/directives/can.js';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const i18n = createI18n({
      // legacy: false,
      locale: props.initialPage.props.locale || 'uk',
      messages,
    });

    watch(
      () => usePage().props?.locale,
      (newLocale) => {
        i18n.global.locale = newLocale;
      }
    );

    return createApp({ render: () => h(App, props) })
      .use(i18n)
      .directive('can', CanDirective)
      .use(plugin)
      .use(ZiggyVue)
      .use(Vue3Toastify, {
        autoClose: 4000,
        position: 'top-right',
        theme: 'light',
        pauseOnHover: true,
        closeOnClick: true,
        hideProgressBar: false,
        transition: 'slide',
        draggable: true,
      })
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
