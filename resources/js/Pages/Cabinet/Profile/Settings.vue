<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import UpdateProfilePhoneForm from '@/Pages/Cabinet/Profile/Partials/UpdateProfilePhoneForm.vue';
import Notification from '@/Pages/Cabinet/Profile/Partials/Notification.vue';
import ProfileMenu from '@/Pages/Cabinet/Profile/Partials/ProfileMenu.vue';
import { useI18n } from 'vue-i18n';
import Socialite from '@/Pages/Cabinet/Profile/Partials/Socialite.vue';
import { ref } from 'vue';

const { t } = useI18n();
const expandedSection = ref('profile');

const toggleSection = (section) => {
  expandedSection.value = expandedSection.value === section ? null : section;
};

const sections = [
  { id: 'profile', title: t('Profile Information'), component: UpdateProfileInformationForm },
  { id: 'phone', title: t('Phone Information'), component: UpdateProfilePhoneForm },
  { id: 'notification', title: t('Notification'), component: Notification },
  { id: 'social', title: t('social.connections'), component: Socialite },
  { id: 'password', title: t('Update Password'), component: UpdatePasswordForm },
  { id: 'delete', title: t('Delete Account'), component: DeleteUserForm },
];
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
              <div
                v-for="section in sections"
                :key="section.id"
                class="mb-2"
              >
                <button
                  type="button"
                  class="flex w-full items-center justify-between p-4 sm:p-6 text-left focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 dark:bg-gray-800 transition-colors bg-gray-200"
                  :aria-expanded="expandedSection === section.id"
                  @click="toggleSection(section.id)"
                >
                  <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ section.title }}
                  </p>
                  <svg
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200"
                    :class="{ 'rotate-180': expandedSection === section.id }"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>

                <div
                  class="overflow-hidden transition-all duration-300 ease-in-out"
                  :class="expandedSection === section.id ? ' opacity-100' : 'max-h-0 opacity-0'"
                >
                  <div class="pb-6 px-4 sm:px-6">
                    <component
                      :is="section.component"
                      class="max-w-2xl"
                    />
                  </div>
                </div>
              </div>
            </div>
            <!--            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">-->
            <!--              <UpdateProfileInformationForm class="max-w-xl" />-->
            <!--            </div>-->

            <!--            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">-->
            <!--              <UpdateProfilePhoneForm class="max-w-xl" />-->
            <!--            </div>-->

            <!--            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">-->
            <!--              <Socialite class="max-w-xl" />-->
            <!--            </div>-->

            <!--            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">-->
            <!--              <UpdatePasswordForm class="max-w-xl" />-->
            <!--            </div>-->

            <!--            <div class="bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-700 mb-4">-->
            <!--              <DeleteUserForm class="max-w-xl" />-->
            <!--            </div>-->
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
