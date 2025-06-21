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
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head :title="$t('register')" />
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <div class="hidden lg:flex items-center justify-center dark:bg-gray-900 p-10">
        <div class="max-w-md text-center">
          <img
            :src="getFullPathForStaticImage('images/auth/login.webp')"
            alt=""
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
              {{ $t('register') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
              {{ $t('already.registered') }}
              <Link
                :href="route('login')"
                class="font-medium text-indigo-600 hover:text-indigo-500"
              >
                {{ $t('login') }}
              </Link>
            </p>
          </div>

          <Head :title="$t('register')" />

          <form @submit.prevent="submit">
            <div>
              <InputLabel
                for="name"
                :value="$t('name')"
              />

              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="name"
              />

              <InputError
                class="mt-2"
                :message="form.errors.name"
              />
            </div>

            <div class="mt-4">
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
                class="mt-2"
                :message="form.errors.email"
              />
            </div>

            <div class="mt-4">
              <InputLabel
                for="password"
                :value="$t('password')"
              />

              <TextInput
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
              />

              <InputError
                class="mt-2"
                :message="form.errors.password"
              />
            </div>

            <div class="mt-4">
              <InputLabel
                for="password_confirmation"
                :value="$t('confirm.password')"
              />

              <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
              />

              <InputError
                class="mt-2"
                :message="form.errors.password_confirmation"
              />
            </div>

            <div>
              <PrimaryButton
                type="submit"
                class="w-full justify-center mt-5 mb-5"
                :disabled="form.processing"
                :class="{ 'opacity-50': form.processing }"
              >
                {{ $t('register') }}
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

<!--<template>-->
<!--  <GuestLayout>-->
<!--    <Head :title="$t('register')" />-->

<!--    <form @submit.prevent="submit">-->
<!--      <div>-->
<!--        <InputLabel-->
<!--          for="name"-->
<!--          :value="$t('name')"-->
<!--        />-->

<!--        <TextInput-->
<!--          id="name"-->
<!--          v-model="form.name"-->
<!--          type="text"-->
<!--          class="mt-1 block w-full"-->
<!--          required-->
<!--          autofocus-->
<!--          autocomplete="name"-->
<!--        />-->

<!--        <InputError-->
<!--          class="mt-2"-->
<!--          :message="form.errors.name"-->
<!--        />-->
<!--      </div>-->

<!--      <div class="mt-4">-->
<!--        <InputLabel-->
<!--          for="email"-->
<!--          :value="$t('email')"-->
<!--        />-->

<!--        <TextInput-->
<!--          id="email"-->
<!--          v-model="form.email"-->
<!--          type="email"-->
<!--          class="mt-1 block w-full"-->
<!--          required-->
<!--          autocomplete="username"-->
<!--        />-->

<!--        <InputError-->
<!--          class="mt-2"-->
<!--          :message="form.errors.email"-->
<!--        />-->
<!--      </div>-->

<!--      <div class="mt-4">-->
<!--        <InputLabel-->
<!--          for="password"-->
<!--          :value="$t('password')"-->
<!--        />-->

<!--        <TextInput-->
<!--          id="password"-->
<!--          v-model="form.password"-->
<!--          type="password"-->
<!--          class="mt-1 block w-full"-->
<!--          required-->
<!--          autocomplete="new-password"-->
<!--        />-->

<!--        <InputError-->
<!--          class="mt-2"-->
<!--          :message="form.errors.password"-->
<!--        />-->
<!--      </div>-->

<!--      <div class="mt-4">-->
<!--        <InputLabel-->
<!--          for="password_confirmation"-->
<!--          :value="$t('confirm.password')"-->
<!--        />-->

<!--        <TextInput-->
<!--          id="password_confirmation"-->
<!--          v-model="form.password_confirmation"-->
<!--          type="password"-->
<!--          class="mt-1 block w-full"-->
<!--          required-->
<!--          autocomplete="new-password"-->
<!--        />-->

<!--        <InputError-->
<!--          class="mt-2"-->
<!--          :message="form.errors.password_confirmation"-->
<!--        />-->
<!--      </div>-->

<!--      <div class="mt-4 flex items-center justify-end">-->
<!--        <Link-->
<!--          :href="route('login')"-->
<!--          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"-->
<!--        >-->
<!--          {{ $t('already.registered') }}-->
<!--        </Link>-->

<!--        <PrimaryButton-->
<!--          class="ms-4"-->
<!--          :class="{ 'opacity-25': form.processing }"-->
<!--          :disabled="form.processing"-->
<!--        >-->
<!--          {{ $t('register') }}-->
<!--        </PrimaryButton>-->
<!--      </div>-->
<!--    </form>-->
<!--  </GuestLayout>-->
<!--</template>-->
