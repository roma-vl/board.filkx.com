```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
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
    <Head :title="$t('reset.password')" />

    <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="flex items-center justify-center p-6 sm:p-8 lg:p-12">
        <div class="w-full max-w-md space-y-8">
          <div class="text-center space-y-2">
            <h2
              class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white transition-colors"
            >
              {{ $t('reset.password') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
              {{ $t('enter_new_password') }}
            </p>
          </div>

          <form
            role="form"
            :aria-label="$t('password_reset_form')"
            class="space-y-6"
            @submit.prevent="submit"
          >
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
                  autofocus
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

            <div class="flex items-center justify-end">
              <PrimaryButton
                type="submit"
                class="flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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
                  {{ $t('resetting') }}
                </span>
                <span v-else>{{ $t('reset.password') }}</span>
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
```
