import Bugsnag from '@bugsnag/js';
import BugsnagPluginVue from '@bugsnag/plugin-vue';
import BugsnagPerformance from '@bugsnag/browser-performance';

Bugsnag.start({
  apiKey: 'cbf3136cd0f8e232fbaa418e5efbfdb5',
  plugins: [new BugsnagPluginVue()],
});
BugsnagPerformance.start({ apiKey: 'cbf3136cd0f8e232fbaa418e5efbfdb5' });

export const bugsnagVue = Bugsnag.getPlugin('vue');
