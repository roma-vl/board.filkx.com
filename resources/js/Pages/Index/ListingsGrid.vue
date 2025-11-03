<script setup>
import { Link, router } from '@inertiajs/vue3';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import { getFullPathForImage } from '@/helpers.js';

const props = defineProps({
  listings: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['toggle-like']);

const toggleLike = (advert) => {
  if (advert.isFavorited) {
    router.delete(route('account.favorites.remove', { advert: advert.id }), {
      replace: true,
      preserveScroll: true,
    });
  } else {
    router.post(route('account.favorites.add', { advert: advert.id }), {
      replace: true,
      preserveScroll: true,
    });
  }
  advert.isFavorited = !advert.isFavorited;
  emit('toggle-like', advert);
};
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <!-- Skeleton Loading -->
    <template v-if="loading">
      <div
        v-for="i in 8"
        :key="i"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden animate-pulse"
      >
        <div class="h-48 bg-gray-200 dark:bg-gray-700" />
        <div class="p-4 space-y-3">
          <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4" />
          <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2" />
          <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3" />
        </div>
      </div>
    </template>

    <!-- Cards -->
    <template v-else>
      <article
        v-for="listing in listings"
        :key="listing.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full"
      >
        <!-- Image & Badges -->
        <div class="relative">
          <img
            :src="getFullPathForImage(listing.firstPhoto)"
            :alt="listing.title"
            class="w-full h-48 object-cover transition-transform duration-500 hover:scale-105"
            loading="lazy"
          >

          <!-- Badges -->
          <div class="absolute top-3 left-3 flex flex-wrap gap-1">
            <span
              v-if="listing.isNew"
              class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium"
            >
              {{ $t('New') }}
            </span>
            <span
              v-if="listing.isPromo"
              class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium"
            >
              {{ $t('Sale') }}
            </span>
          </div>

          <!-- Favorite Button -->
          <button
            type="button"
            class="absolute top-3 right-3 p-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-md hover:shadow-lg transition-all duration-200 hover:scale-110"
            :aria-label="listing.isFavorited ? $t('Remove from favorites') : $t('Add to favorites')"
            @click="toggleLike(listing)"
          >
            <HeartIcon
              v-if="!listing.isFavorited"
              class="w-5 h-5 text-gray-600 dark:text-gray-300 hover:text-red-500 transition-colors"
            />
            <HeartSolidIcon
              v-else
              class="w-5 h-5 text-red-500"
            />
          </button>
        </div>

        <!-- Content -->
        <div class="p-4 flex flex-col flex-1">
          <!-- Category -->
          <p
            v-if="listing.category"
            class="text-xs font-medium text-indigo-600 dark:text-indigo-400 mb-2 uppercase tracking-wide"
          >
            {{ listing.category }}
          </p>

          <!-- Title -->
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
            <Link
              :href="route('adverts.show', listing.id)"
              class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
            >
              {{ listing.title }}
            </Link>
          </h3>

          <!-- Price -->
          <p class="text-xl font-bold text-green-600 dark:text-green-400 mb-2">
            {{ listing.price }} ₴
          </p>

          <!-- Location & Date -->
          <div class="mt-auto pt-4 flex justify-between items-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ listing.city }}
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500">
              {{ new Date(listing.createdAt).toLocaleDateString() }}
            </p>
          </div>
        </div>
      </article>
    </template>
  </div>
</template>
