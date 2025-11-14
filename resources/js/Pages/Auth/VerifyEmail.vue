```vue
<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  status: {
    type: String,
    default: '',
  },
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
  <GuestLayout>
    <Head :title="$t('email.verify')" />

    <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="flex items-center justify-center p-6 sm:p-8 lg:p-12">
        <div class="w-full max-w-md space-y-8">
          <!-- Header -->
          <div class="text-center space-y-2">
            <h2
              class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white transition-colors"
            >
              {{ $t('email.verify') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
              {{ $t('email.verify.text') }}
            </p>
          </div>

          <!-- Success Message -->
          <div
            v-if="verificationLinkSent"
            class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800"
          >
            <p class="text-sm text-green-700 dark:text-green-400">
              {{ $t('email.verify.text.send') }}
            </p>
          </div>

          <!-- Verification Form -->
          <form
            role="form"
            :aria-label="$t('verification_form')"
            class="space-y-6"
            @submit.prevent="submit"
          >
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <PrimaryButton
                type="submit"
                class="flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto"
                :disabled="form.processing"
                :aria-busy="form.processing"
              >
                <span
                  v-if="form.processing"
                  class="flex items-center"
                >
                  <svg
                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <circle
                      class="opacity-25"
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      stroke-width="4"
                    />
                    <path
                      class="opacity-75"
                      fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    />
                  </svg>
                  {{ $t('sending') }}
                </span>
                <span v-else>{{ $t('email.verify.resend') }}</span>
              </PrimaryButton>

              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded px-3 py-2 transition-colors duration-200 w-full sm:w-auto text-center"
              >
                {{ $t('logout') }}
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
```
