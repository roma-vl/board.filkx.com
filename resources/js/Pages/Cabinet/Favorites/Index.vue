<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Cabinet/Profile/Partials/ProfileMenu.vue';
import { computed } from 'vue';
import AdvertList from '@/Components/AdvertList.vue';

const favoriteAdverts = computed(() => usePage().props.favoriteAdverts);

const routes = {
  remove: 'cabinet.favorites.remove',
};
</script>

<template>
  <Head :title="$t('saved.announcement')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <ProfileMenu :active-tab="'cabinet.favorites.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                {{ $t('saved.announcement') }}
              </h2>
            </div>

            <AdvertList
              :adverts="favoriteAdverts"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
