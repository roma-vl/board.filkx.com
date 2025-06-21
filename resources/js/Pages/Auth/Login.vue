<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};

const loginWithGoogle = () => {
  window.location.href = route('google.redirect');
};
</script>
<template>
  <GuestLayout>
    <Head :title="$t('login')" />
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <div class="hidden lg:flex items-center justify-center dark:bg-gray-900 p-10">
        <div class="max-w-md text-center">
          <img
            :src="getFullPathForStaticImage('images/auth/login.webp')"
            alt="Login illustration"
            class="mb-16 w-full h-auto"
          >
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $t('welcome_back') }}
          </h2>
        </div>
      </div>

      <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
          <div>
            <h2
              class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white"
            >
              {{ $t('login') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
              {{ $t('dont_have_account') }}
              <Link
                :href="route('register')"
                class="font-medium text-indigo-600 hover:text-indigo-500"
              >
                {{ $t('register') }}
              </Link>
            </p>
          </div>
          <div class="flex flex-col gap-4">
            <button
              type="button"
              class="btn-social-google"
              @click="loginWithGoogle()"
            >
              <GoogleIcon />
              <span class="icon-google" /> Sign in with Google
            </button>
            <button
              type="button"
              class="btn-social-facebook"
            >
              <FacebookIcon />
              <span class="icon-facebook" /> Sign in with Facebook
            </button>
          </div>
          <form
            class="mt-8 space-y-6"
            @submit.prevent="submit"
          >
            <div class="relative">
              <InputLabel
                for="email"
                :value="$t('email')"
              />
              <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autocomplete="username"
              />
              <InputError
                :message="form.errors.email"
                class="mt-2"
              />
            </div>

            <div class="relative">
              <InputLabel
                for="password"
                :value="$t('password')"
              />
              <TextInput
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                class="mt-1 block w-full"
                required
                autocomplete="current-password"
              />
              <button
                type="button"
                class="absolute right-3 top-9 text-sm text-indigo-500"
                @click="showPassword = !showPassword"
              >
                {{ showPassword ? $t('hide') : $t('show') }}
              </button>
              <InputError
                :message="form.errors.password"
                class="mt-2"
              />
            </div>

            <div class="flex items-center justify-between">
              <label class="flex items-center">
                <Checkbox
                  v-model:checked="form.remember"
                  name="remember"
                />
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">{{
                  $t('remember')
                }}</span>
              </label>
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm text-indigo-600 hover:text-indigo-500"
              >
                {{ $t('forgot.password') }}
              </Link>
            </div>

            <div>
              <PrimaryButton
                type="submit"
                class="w-full justify-center"
                :disabled="form.processing"
                :class="{ 'opacity-50': form.processing }"
              >
                {{ $t('login') }}
              </PrimaryButton>
            </div>

            <p class="text-xs text-gray-400 text-center">
              {{ $t('accept_terms') }}
              <Link
                href="/terms"
                class="underline"
              >
                {{ $t('terms') }}
              </Link>
              {{ $t('and') }}
              <Link
                href="/privacy"
                class="underline"
              >
                {{ $t('privacy') }}
              </Link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<style scoped>
.btn-social-google {
  @apply bg-white text-gray-700 border hover:bg-gray-100 rounded-lg px-5 py-2.5 w-full flex items-center justify-center gap-3 transition;
}
.btn-social-facebook {
  @apply bg-[#1877F2] text-white rounded-lg px-5 py-2.5 w-full flex items-center justify-center gap-3 hover:bg-[#1877F2]/90 transition;
}
</style>
