<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { fullPath, getFullPathForImage } from '@/helpers.js';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import CookieBanner from '@/Components/CookieBanner.vue';

const categories = usePage().props.categories;
const news = usePage().props.news;
const vip = usePage().props.vip;

import { useSearch } from '@/composables/useSearch.js';
import SearchInput from '@/Components/Search/SearchInput.vue';
import LocationSelector from '@/Components/Search/LocationSelector.vue';

const { searchQuery, cityIdSearchQuery, search } = useSearch();
const handleCitySelect = (slug) => {
  cityIdSearchQuery.value = slug;
};
const handleSearch = (text) => {
  searchQuery.value = text;
};

const openCategory = ref(null);
const flash = computed(() => usePage().props.flash);
const toggleLike = (advert) => {
  if (advert.is_favorited === true) {
    router.delete(route('account.favorites.remove', { advert: advert.id }));
  } else {
    router.post(route('account.favorites.add', { advert: advert.id }));
  }
  advert.is_favorited = !advert.is_favorited;
};

const toggleCategory = (categoryId) => {
  if (openCategory.value === categoryId) {
    openCategory.value = null;
  } else {
    openCategory.value = categoryId;
  }
};

const selectedCategory = computed(() => {
  const category = categories.find((c) => c.id === openCategory.value);
  return category ? category : '';
});

const subCategories = computed(() => {
  const category = categories.find((c) => c.id === openCategory.value);
  return category ? category.children : [];
});
</script>

<template>
  <Head :title="$t('main')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-3xl p-6 dark:bg-gray-700 dark:text-gray-100"
        >
          <div class="container mx-auto">
            <FlashMessage :flash="flash" />
            <div
              class="flex flex-col md:flex-row items-center gap-6 bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 p-6 rounded-3xl shadow-2xl border border-purple-200/30"
            >
              <SearchInput
                v-model="searchQuery"
                @select-suggestion="handleSearch"
              />
              <div class="flex items-center gap-4">
                <LocationSelector
                  v-model="cityIdSearchQuery"
                  @select-city="handleCitySelect"
                />
                <button
                  class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-semibold rounded-full hover:scale-105 active:scale-95 transition-transform duration-150 shadow-lg"
                  @click="search"
                >
                  🔍 {{ $t('search.button') }}
                </button>
              </div>
            </div>

            <!-- Категорії -->
            <section class="my-10">
              <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-300">
                {{ $t('divided.into.services') }}
              </h2>
              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6">
                <template
                  v-for="category in categories"
                  :key="category.id"
                >
                  <div class="text-center">
                    <button
                      class="p-4 rounded-2xl bg-white hover:bg-gray-100 shadow-lg w-full h-full flex flex-col justify-center items-center"
                      @click="toggleCategory(category.id)"
                    >
                      <img
                        src="https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png"
                        :alt="category.name"
                        class="w-12 h-12"
                      >
                      <span class="text-sm mt-2 font-medium text-gray-700">
                        {{ category.name }}
                      </span>
                    </button>
                  </div>
                </template>

                <transition name="fade">
                  <div
                    v-if="openCategory && subCategories.length > 0"
                    class="col-span-full bg-white rounded-2xl shadow-lg p-6"
                  >
                    <p class="text-sm font-semibold mb-4">
                      {{ $t('look.at.all.the.announcement.in') }}
                      <a
                        :href="fullPath() + '/' + selectedCategory.slug"
                        class="text-blue-600 hover:underline"
                      >
                        {{ selectedCategory.name }}
                      </a>
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                      <template
                        v-for="subCategory in subCategories"
                        :key="subCategory.id"
                      >
                        <a
                          :href="fullPath() + '/' + selectedCategory.slug + '/' + subCategory.slug"
                          class="text-sm text-gray-700 hover:underline"
                        >
                          {{ subCategory.name }}
                        </a>
                      </template>
                    </div>
                  </div>
                </transition>
              </div>
            </section>

            <!-- VIP-оголошення -->
            <section class="bg-white p-6 dark:bg-gray-800 rounded-md">
              <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-300">
                {{ $t('vip.announcement') }}
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template
                  v-for="listing in vip"
                  :key="listing.id"
                >
                  <div
                    class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 bg-white"
                  >
                    <img
                      :src="getFullPathForImage(listing.first_photo?.file)"
                      :alt="listing.title"
                      class="w-full h-48 object-cover"
                    >
                    <div class="p-4">
                      <h3 class="text-lg font-semibold text-gray-800">
                        {{ listing.title }}
                      </h3>
                      <p class="text-green-600 font-bold text-md">
                        {{ listing.price }}
                      </p>
                      <div class="flex justify-between items-center mt-4">
                        <button @click="toggleLike(listing)">
                          <HeartIcon
                            v-if="!listing.is_favorited"
                            class="w-6 h-6 text-gray-400 hover:text-red-400 transition"
                          />
                          <HeartSolidIcon
                            v-else
                            class="w-6 h-6 text-red-500"
                          />
                        </button>
                        <Link
                          :href="route('adverts.show', listing.id)"
                          class="text-blue-600 hover:underline"
                        >
                          {{ $t('more.details') }}
                        </Link>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </section>

            <!-- Останні оголошення -->
            <section class="bg-white p-6 mt-10 dark:bg-gray-800 rounded-md">
              <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-300">
                {{ $t('last.announcement') }}
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template
                  v-for="listing in news"
                  :key="listing.id"
                >
                  <div
                    class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 bg-white"
                  >
                    <img
                      :src="getFullPathForImage(listing.first_photo?.file)"
                      alt="Фото"
                      class="w-full h-48 object-cover"
                    >
                    <div class="p-4">
                      <h3 class="text-lg font-semibold text-gray-800">
                        {{ listing.title }}
                      </h3>
                      <p class="text-green-600 font-bold text-md">
                        {{ listing.price }}
                      </p>
                      <div class="flex justify-between items-center mt-4">
                        <button @click="toggleLike(listing)">
                          <HeartIcon
                            v-if="!listing.is_favorited"
                            class="w-6 h-6 text-gray-400 hover:text-red-400 transition"
                          />
                          <HeartSolidIcon
                            v-else
                            class="w-6 h-6 text-red-500"
                          />
                        </button>
                        <Link
                          :href="route('adverts.show', listing.id)"
                          class="text-blue-600 hover:underline"
                        >
                          {{ $t('more.details') }}
                        </Link>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
    <CookieBanner />
  </AuthenticatedLayout>
</template>

<style scoped></style>
