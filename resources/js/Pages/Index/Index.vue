<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StaticSection from '@/Pages/Index/StaticSection.vue';
import CookieBanner from '@/Components/CookieBanner.vue';
import SearchBar from '@/Pages/Index/SearchBar.vue';
import CategoriesGrid from '@/Pages/Index/CategoriesGrid.vue';
import VipListings from '@/Pages/Index/VipListings.vue';
import LatestListings from '@/Pages/Index/LatestListings.vue';
import {getFullPathForImage} from "@/helpers.js";
const { categories, news, vip } = usePage().props;
</script>

<template>
  <Head :title="$t('home.main')" />
  <AuthenticatedLayout>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 px-4">
      <div class="pt-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="overflow-hidden rounded-2xl shadow-xl">
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8">
              <SearchBar />
            </div>
          </div>
        </div>
      </div>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-4">
            <div class="flex items-center rounded-xl bg-gradient-to-r from-blue-600 to-fuchsia-500 px-8 text-white shadow-lg">
                <div class="my-10 lg:my-8 lg:w-1/2">
                    <h1 class="text-2xl font-bold leading-tight">Розмісти оголошення — отримай більше переглядів</h1>
                    <p class="mt-4 text-lg opacity-95">
                        Збільш шанси знайти покупця, працівника чи клієнта — з найкращими інструментами для розміщення оголошень.
                    </p>
                    <button type="button" class="group mt-4 flex items-center text-xl font-semibold leading-tight text-white">
                        Почати зараз
                        <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:ml-8 ml-4 h-7 w-7 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
                <div class="hidden h-72 w-1/2 flex-shrink-0 justify-center lg:flex">
                    <img :src="getFullPathForImage('/images/main-images/main.png')" alt="Користувач розміщує оголошення на платформі" class="rounded-lg shadow-lg" />
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
