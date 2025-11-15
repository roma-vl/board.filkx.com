<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import UpdateProfilePhoneForm from '@/Pages/Cabinet/Profile/Partials/UpdateProfilePhoneForm.vue';
import ProfileMenu from '@/Pages/Cabinet/Profile/Partials/ProfileMenu.vue';
import { useI18n } from 'vue-i18n';
import Socialite from '@/Pages/Cabinet/Profile/Partials/Socialite.vue';

const { t } = useI18n();
</script>

<template>
  <Head :title="t('Settings')" />

  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-800">
          <ProfileMenu :active-tab="'cabinet.profile.settings'" />
          <div class="mx-auto max-w-7xl">
            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">
              <UpdateProfileInformationForm class="max-w-xl" />
            </div>

            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">
              <UpdateProfilePhoneForm class="max-w-xl" />
            </div>

            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">
              <Socialite class="max-w-xl" />
            </div>

            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">
              <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">
              <DeleteUserForm class="max-w-xl" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
