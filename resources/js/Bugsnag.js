import Bugsnag from '@bugsnag/js';
import BugsnagPluginVue from '@bugsnag/plugin-vue';
import BugsnagPerformance from '@bugsnag/browser-performance';

let bugsnagVue = null;

if (import.meta.env.PROD) {
  Bugsnag.start({
    apiKey: import.meta.env.VITE_BUGSNAG_API_KEY || '',
    plugins: [new BugsnagPluginVue()],
    releaseStage: import.meta.env.VITE_APP_ENV || 'development',
    onError: (event) => {
      if (event.request?.url?.startsWith('data:image')) {
        delete event.request.url;
      }
    },
  });

  BugsnagPerformance.start({
    apiKey: import.meta.env.VITE_BUGSNAG_API_KEY || '',
  });

  bugsnagVue = Bugsnag.getPlugin('vue');
}

export { bugsnagVue };
