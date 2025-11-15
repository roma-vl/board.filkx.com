```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { getFullPathForStaticImage } from '@/helpers.js';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onError: () => {
      const firstErrorField = document.querySelector('[aria-invalid="true"]');
      if (firstErrorField) {
        firstErrorField.focus();
      }
    },
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head :title="$t('register')" />
    <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div
          class="hidden lg:flex items-center justify-center dark:from-gray-800 dark:to-gray-900 p-12 transition-colors duration-300"
        >
          <div class="max-w-md text-center space-y-6">
            <img
              :src="getFullPathForStaticImage('images/auth/login.webp')"
              :alt="$t('register_illustration_alt')"
              class="w-full h-auto object-contain drop-shadow-lg dark:drop-shadow-none"
              loading="lazy"
              decoding="async"
            >
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">
              {{ $t('welcome_back') }}
            </h2>
          </div>
        </div>
        <div
          class="flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-white dark:bg-gray-900 transition-colors duration-300"
        >
          <div class="w-full max-w-md space-y-8">
            <div class="text-center space-y-2">
              <h2
                class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white transition-colors"
              >
                {{ $t('register') }}
              </h2>
              <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                {{ $t('already.registered') }}
                <Link
                  :href="route('login')"
                  class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                >
                  {{ $t('login') }}
                </Link>
              </p>
            </div>
            <form
              role="form"
              :aria-label="$t('register_form')"
              class="space-y-6"
              @submit.prevent="submit"
            >
              <div>
                <InputLabel
                  for="name"
                  :value="$t('name')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1">
                  <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                    :required="true"
                    autofocus
                    autocomplete="name"
                    :aria-invalid="!!form.errors.name"
                    :aria-describedby="form.errors.name ? 'name-error' : undefined"
                  />
                </div>
                <InputError
                  v-if="form.errors.name"
                  id="name-error"
                  :message="form.errors.name"
                  class="mt-1"
                />
              </div>
              <div>
                <InputLabel
                  for="email"
                  :value="$t('email')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1">
                  <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                    :required="true"
                    autocomplete="username"
                    :aria-invalid="!!form.errors.email"
                    :aria-describedby="form.errors.email ? 'email-error' : undefined"
                  />
                </div>
                <InputError
                  v-if="form.errors.email"
                  id="email-error"
                  :message="form.errors.email"
                  class="mt-1"
                />
              </div>
              <div>
                <InputLabel
                  for="password"
                  :value="$t('password')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1">
                  <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                    :required="true"
                    autocomplete="new-password"
                    :aria-invalid="!!form.errors.password"
                    :aria-describedby="form.errors.password ? 'password-error' : undefined"
                  />
                </div>
                <InputError
                  v-if="form.errors.password"
                  id="password-error"
                  :message="form.errors.password"
                  class="mt-1"
                />
              </div>
              <div>
                <InputLabel
                  for="password_confirmation"
                  :value="$t('confirm.password')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1">
                  <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                    :required="true"
                    autocomplete="new-password"
                    :aria-invalid="!!form.errors.password_confirmation"
                    :aria-describedby="
                      form.errors.password_confirmation ? 'password-confirmation-error' : undefined
                    "
                  />
                </div>
                <InputError
                  v-if="form.errors.password_confirmation"
                  id="password-confirmation-error"
                  :message="form.errors.password_confirmation"
                  class="mt-1"
                />
              </div>
              <div>
                <PrimaryButton
                  type="submit"
                  class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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
                    {{ $t('registering') }}
                  </span>
                  <span v-else>{{ $t('register') }}</span>
                </PrimaryButton>
              </div>
              <p class="text-xs text-center text-gray-500 dark:text-gray-400 transition-colors">
                {{ $t('accept_terms') }}
                <Link
                  href="/terms"
                  class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {{ $t('terms') }}
                </Link>
                {{ $t('and') }}
                <Link
                  href="/privacy"
                  class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {{ $t('privacy') }}
                </Link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
