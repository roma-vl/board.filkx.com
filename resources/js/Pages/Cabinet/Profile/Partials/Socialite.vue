<script setup>
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import GoogleIcon from '@/Components/Icon/GoogleIcon.vue';
import FacebookIcon from '@/Components/Icon/FacebookIcon.vue';
import GithubFooterIcon from '@/Components/Icon/GithubFooterIcon.vue';

const { t } = useI18n();
const user = usePage().props.auth.user;
const linkedAccounts = ref(user.social_accounts ?? []);

const isLinked = (provider) => linkedAccounts.value.some((a) => a.provider === provider);

const getAccount = (provider) => linkedAccounts.value.find((a) => a.provider === provider);

const connect = (provider) => {
  window.location.href = route('social.connect', provider);
};

const disconnect = (provider) => {
  if (confirm(t('social.disconnect.confirm', { provider }))) {
    Inertia.delete(route('social.disconnect', provider));
  }
};
</script>

<template>
  <section class="mt-10">
    <header>
      <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
        {{ t('social.connections') }}
      </h2>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ t('social.connections.desc') }}
      </p>
    </header>

    <div class="mt-6 grid gap-6">
      <div
        class="relative p-5 border rounded-xl bg-white dark:bg-gray-800 shadow-sm transition-all duration-300 hover:shadow-md"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <GoogleIcon />
            <div class="flex flex-col">
              <span class="capitalize font-medium text-gray-900 dark:text-white"> Google </span>
              <span
                v-if="isLinked('google')"
                class="text-xs text-gray-500 dark:text-gray-400"
              >
                {{ t('linked.as') }} {{ getAccount('google')?.provider_user_id ?? '...' }}
              </span>
            </div>
          </div>

          <transition
            name="fade"
            mode="out-in"
          >
            <button
              v-if="isLinked('google')"
              key="google-unlink"
              class="px-4 py-1.5 text-sm text-red-600 hover:text-red-700 border border-red-600 hover:border-red-700 rounded-md transition-all"
              @click="disconnect('google')"
            >
              {{ t('unlink') }}
            </button>
            <button
              v-else
              key="google-link"
              class="px-4 py-1.5 text-sm text-indigo-600 hover:text-white border border-indigo-600 hover:bg-indigo-600 rounded-md transition-all"
              @click="connect('google')"
            >
              {{ t('link') }}
            </button>
          </transition>
        </div>
      </div>

      <div
        class="relative p-5 border rounded-xl bg-gray-100 dark:bg-gray-700/30 opacity-60 cursor-not-allowed"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <FacebookIcon class="bg-blue-800" />
            <div class="flex flex-col">
              <span class="capitalize font-medium text-gray-500 dark:text-gray-300">
                Facebook
              </span>
              <span class="text-xs text-gray-400 dark:text-gray-400">
                {{ t('coming.soon') }}
              </span>
            </div>
          </div>

          <span class="px-4 py-1.5 text-sm border border-gray-400 rounded-md text-gray-500">
            {{ t('soon') }}
          </span>
        </div>
      </div>

      <!-- GitHub (disabled) -->
      <div
        class="relative p-5 border rounded-xl bg-gray-100 dark:bg-gray-700/30 opacity-60 cursor-not-allowed"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <GithubFooterIcon />
            <div class="flex flex-col">
              <span class="capitalize font-medium text-gray-500 dark:text-gray-300"> GitHub </span>
              <span class="text-xs text-gray-400 dark:text-gray-400">
                {{ t('coming.soon') }}
              </span>
            </div>
          </div>

          <span class="px-4 py-1.5 text-sm border border-gray-400 rounded-md text-gray-500">
            {{ t('soon') }}
          </span>
        </div>
      </div>
    </div>
  </section>
</template>
