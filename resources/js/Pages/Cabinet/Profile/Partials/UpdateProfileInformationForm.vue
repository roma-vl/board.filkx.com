<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps({
  mustVerifyEmail: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: '',
  },
});

const user = usePage().props.auth.user;

const isEmailVerified = computed(() => {
  return new Date(user.email_verified_at).getTime() > 0;
});

const form = useForm({
  first_name: user.first_name,
  name: user.name,
  last_name: user.last_name,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <p class="mt-5 text text-gray-600 dark:text-gray-400">
        {{ t("Update your account's profile information and email address.") }}
      </p>
    </header>
    <form
      class="mt-6 space-y-6"
      @submit.prevent="form.patch(route('cabinet.profile.update'))"
    >
      <div>
        <InputLabel
          for="first_name"
          :value="t('First Name')"
        />
        <TextInput
          id="first_name"
          v-model="form.first_name"
          type="text"
          class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-800"
          required
          autocomplete="first_name"
        />
        <InputError
          class="mt-2"
          :message="form.errors.first_name"
        />
      </div>

      <div>
        <InputLabel
          for="name"
          :value="t('Name')"
        />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-800"
          required
          autocomplete="name"
        />
        <InputError
          class="mt-2"
          :message="form.errors.name"
        />
      </div>

      <div>
        <InputLabel
          for="last_name"
          :value="t('Last Name')"
        />
        <TextInput
          id="last_name"
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-800"
          autocomplete="last_name"
        />
        <InputError
          class="mt-2"
          :message="form.errors.last_name"
        />
      </div>

      <div>
        <InputLabel
          for="email"
          :value="t('Email')"
        />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-800"
          required
          autocomplete="username"
        />
        <div
          v-if="!isEmailVerified"
          class="text-gray-400 flex justify-end"
        >
          {{ t('Not confirmed') }}
        </div>
        <InputError
          class="mt-2"
          :message="form.errors.email"
        />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="mt-2 text-sm text-gray-800">
          {{ t('Your email address is unverified.') }}
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            {{ t('Click here to re-send the verification email.') }}
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600"
        >
          {{ t('A new verification link has been sent to your email address.') }}
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">
          {{ t('Save') }}
        </PrimaryButton>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600"
          >
            {{ t('Saved') }}
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
