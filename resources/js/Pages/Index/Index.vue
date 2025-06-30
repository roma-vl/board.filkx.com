<script setup>
import { usePage } from '@inertiajs/vue3';

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
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-3xl p-6 dark:bg-gray-700 dark:text-gray-100"
        >
          <div class="container mx-auto">
            <SearchBar />
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
