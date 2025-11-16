<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StaticSection from '@/Pages/Index/StaticSection.vue';
import CookieBanner from '@/Components/CookieBanner.vue';
import SearchBar from '@/Pages/Index/SearchBar.vue';
import CategoriesGrid from '@/Pages/Index/CategoriesGrid.vue';
import VipListings from '@/Pages/Index/VipListings.vue';
import LatestListings from '@/Pages/Index/LatestListings.vue';
const { categories, news, vip } = usePage().props;
</script>

<template>
  <Head :title="$t('home.main')" />
  <AuthenticatedLayout>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 px-4">
      <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="overflow-hidden rounded-2xl shadow-xl">
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8">
              <SearchBar />
            </div>
          </div>
        </div>
      </div>

      <div class="pb-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
            <CategoriesGrid :categories="categories" />
            <VipListings :vip="vip" />
            <LatestListings :news="news" />
            <StaticSection />
          </div>
        </div>
      </div>
    </div>

    <CookieBanner />
  </AuthenticatedLayout>
</template>
