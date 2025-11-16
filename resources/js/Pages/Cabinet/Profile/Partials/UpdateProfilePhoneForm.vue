<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const user = usePage().props.auth.user;

const form = useForm({
  phone: user.phone,
});
</script>

<template>
  <section>
    <header>
      <p class="mt-5 text text-gray-600 dark:text-gray-400">
        {{ t("Update your account's phone information.") }}
      </p>
    </header>

    <form
      class="mt-6 space-y-6"
      @submit.prevent="form.patch(route('cabinet.profile.phone.update'))"
    >
      <div>
        <InputLabel
          for="phone"
          :value="t('Phone')"
        />

        <TextInput
          id="phone"
          v-model="form.phone"
          type="tel"
          class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-800"
          autocomplete="phone"
        />

        <div
          v-if="Number(user.phone_verified) === 0"
          class="text-gray-400 flex justify-end"
        >
          {{ t('Not confirmed') }}
        </div>

        <InputError
          class="mt-2"
          :message="form.errors.phone"
        />
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
