<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProfileMenu from '@/Pages/Cabinet/Profile/Partials/ProfileMenu.vue';
import AvatarUploader from '@/Components/AvatarUploader.vue';

const { t } = useI18n();
const user = usePage().props.auth.user;
</script>

<template>
  <Head :title="t('Profile')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <ProfileMenu :active-tab="'cabinet.profile.index'" />
          <div class="flex min-h-[400px] p-3 px-6">
            <div
              class="w-1/3 bg-white flex items-center justify-center overflow-hidden dark:bg-gray-800 rounded-md shadow-md"
            >
              <AvatarUploader />
            </div>

            <div
              class="w-2/3 p-6 bg-white overflow-hidden flex flex-col m-0 ml-4 dark:bg-gray-800 rounded-md shadow-md"
            >
              <div class="grid grid-cols-2 gap-4">
                <h2 class="text-2xl font-bold mb-4">
                  {{ t('User Profile') }}
                </h2>
                <a
                  :href="route('cabinet.profile.settings')"
                  class="flex justify-end font-bold"
                >{{
                  t('Edit')
                }}</a>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('ID') }}:</strong> {{ user.id }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('First Name') }}:</strong> {{ user.first_name }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Last Name') }}:</strong> {{ user.last_name }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Email') }}:</strong> {{ user.email }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Locale') }}:</strong> {{ user.locale }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Phone') }}:</strong> {{ user.phone }}
                  </p>
                  <PrimaryButton
                    v-if="Number(user.phone_verified) === 0"
                    class="dark:text-gray-200 dark:bg-gray-600 dark:hover:bg-gray-700 mt-4"
                  >
                    <a :href="route('cabinet.profile.phone.request')">{{ t('Verify') }}</a>
                  </PrimaryButton>
                </div>
                <div>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Created At') }}:</strong> {{ user.created_at }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Updated At') }}:</strong> {{ user.updated_at }}
                  </p>
                  <p class="text-gray-600 dark:text-gray-200">
                    <strong>{{ t('Verified') }}:</strong>
                    {{ user.email_verified_at ? t('Yes') : t('No') }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
