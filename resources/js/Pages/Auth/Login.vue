```vue
<script setup>
import { ref, nextTick } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import GoogleIcon from '@/Components/Icon/GoogleIcon.vue';
import FacebookIcon from '@/Components/Icon/FacebookIcon.vue';
import { getFullPathForStaticImage } from '@/helpers.js';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
  canResetPassword: Boolean,
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const showPassword = ref(false);

const togglePassword = async () => {
  showPassword.value = !showPassword.value;
  await nextTick();
  const passwordField = document.getElementById('password');
  passwordField?.focus();
};

const submit = () => {
  form.post(route('login'), {
    onError: async () => {
      await nextTick();
      const firstErrorField = document.querySelector('[aria-invalid="true"]');
      firstErrorField?.focus();
    },
    onFinish: () => form.reset('password'),
  });
};

const connect = (provider) => {
  window.location.href = route('social.register', provider);
};
</script>

<template>
  <GuestLayout>
    <Head :title="$t('login')" />
    <div class="bg-white dark:bg-gray-900 transition-colors duration-300 rounded-md">
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <!-- Illustration Section -->
        <div
          class="hidden lg:flex items-center justify-center dark:from-gray-800 dark:to-gray-900 p-12 transition-colors duration-300"
        >
          <div class="max-w-md text-center space-y-6">
            <img
              :src="getFullPathForStaticImage('images/auth/login.webp')"
              alt="Login illustration"
              class="w-full h-auto object-contain drop-shadow-lg dark:drop-shadow-none"
              loading="lazy"
              decoding="async"
            >
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">
              {{ $t('welcome_back') }}
            </h2>
          </div>
        </div>

        <!-- Form Section -->
        <div
          class="flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-white dark:bg-gray-900 transition-colors duration-300"
        >
          <div class="w-full max-w-md space-y-8">
            <!-- Header -->
            <div class="text-center space-y-2">
              <h2
                class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white transition-colors"
              >
                {{ $t('login') }}
              </h2>
              <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                {{ $t('dont_have_account') }}
                <Link
                  :href="route('register')"
                  class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                >
                  {{ $t('register') }}
                </Link>
              </p>
            </div>

            <!-- Social Auth Buttons -->
            <div class="flex flex-col gap-3">
              <button
                type="button"
                class="flex items-center justify-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :aria-label="$t('sign_in_with_google')"
                @click="connect('google')"
              >
                <GoogleIcon class="w-5 h-5" />
                <span class="text-sm font-medium">Sign in with Google</span>
              </button>
              <button
                type="button"
                class="flex items-center justify-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                :aria-label="$t('sign_in_with_facebook')"
                :disabled="true"
              >
                <FacebookIcon class="w-5 h-5" />
                <span class="text-sm font-medium">Sign in with Facebook</span>
              </button>
            </div>

            <!-- Divider -->
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600" />
              </div>
              <div class="relative flex justify-center text-sm">
                <span
                  class="px-2 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 transition-colors"
                >
                  {{ $t('or_continue_with_email') }}
                </span>
              </div>
            </div>

            <!-- Login Form -->
            <form
              role="form"
              :aria-label="$t('login_form')"
              class="space-y-6"
              @submit.prevent="submit"
            >
              <!-- Email Field -->
              <div>
                <InputLabel
                  for="email"
                  :value="$t('email')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1 relative">
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

              <!-- Password Field -->
              <div>
                <InputLabel
                  for="password"
                  :value="$t('password')"
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                />
                <div class="mt-1 relative">
                  <TextInput
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                    :required="true"
                    autocomplete="current-password"
                    :aria-invalid="!!form.errors.password"
                    :aria-describedby="form.errors.password ? 'password-error' : undefined"
                  />
                  <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                    :aria-label="showPassword ? $t('hide_password') : $t('show_password')"
                    @click="togglePassword"
                  >
                    <span class="text-sm font-medium">
                      {{ showPassword ? $t('hide') : $t('show') }}
                    </span>
                  </button>
                </div>
                <InputError
                  v-if="form.errors.password"
                  id="password-error"
                  :message="form.errors.password"
                  class="mt-1"
                />
              </div>

              <!-- Remember Me & Forgot Password -->
              <div class="flex items-center justify-between">
                <label class="flex items-center">
                  <Checkbox
                    v-model:checked="form.remember"
                    name="remember"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                  />
                  <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 transition-colors">
                    {{ $t('remember') }}
                  </span>
                </label>
                <Link
                  v-if="canResetPassword"
                  :href="route('password.request')"
                  class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                >
                  {{ $t('forgot.password') }}
                </Link>
              </div>

              <!-- Submit Button -->
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
                    {{ $t('logging_in') }}
                  </span>
                  <span v-else>{{ $t('login') }}</span>
                </PrimaryButton>
              </div>
            </form>

            <!-- Terms & Privacy -->
            <p class="text-xs text-center text-gray-500 dark:text-gray-400 transition-colors">
              {{ $t('accept') }}
              <Link
                href="/"
                class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                target="_blank"
                rel="noopener noreferrer"
              >
                {{ $t('terms') }}
              </Link>
              {{ $t('and') }}
              <Link
                href="/"
                class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded"
                target="_blank"
                rel="noopener noreferrer"
              >
                {{ $t('privacy') }}
              </Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
