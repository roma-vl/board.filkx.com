<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import BannerList from '@/Pages/Account/Banner/Partials/BannerList.vue';

const banners = usePage().props.banners;

const routes = {
  edit: 'account.banners.edit',
  remove: 'account.banners.destroy',
  show: 'account.banners.show',
};
</script>

<template>
  <Head :title="$t('banners.pageTitle')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <ProfileMenu :active-tab="'account.banners.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                {{ $t('banners.title') }}
              </h2>

              <a
                :href="route('account.banners.create')"
                class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-400 hover:text-violet-700"
              >
                + {{ $t('banners.add') }}
              </a>
            </div>

            <BannerList
              :banners="banners"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
