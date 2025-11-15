<script setup>
import { getDateFormatFromLocale, getFullPathForImage, truncateContent } from '@/helpers.js';
import { Link, router } from '@inertiajs/vue3';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';

const props = defineProps({
  advert: {
    type: Object,
    default: () => ({}),
  },
});

const toggleLike = (advert) => {
  if (advert.is_favorited === true) {
    router.delete(route('cabinet.favorites.remove', { advert: advert.id }));
  } else {
    router.post(route('cabinet.favorites.add', { advert: advert.id }));
  }
  advert.is_favorited = !advert.is_favorited;
};
</script>

<template>
  <div
    class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-2xl transition-all duration-300 dark:bg-gray-800 dark:border-gray-900"
  >
    <img
      :src="getFullPathForImage(advert.first_photo?.file)"
      :alt="advert.title"
      class="object-cover w-full h-52 hover:scale-105 transition-transform duration-300"
    >
    <div class="p-5 space-y-2">
      <div class="flex justify-between items-start">
        <Link
          :href="route('adverts.show', advert.id)"
          class="text-lg font-semibold text-gray-800 hover:text-indigo-600 transition-colors duration-200 dark:text-gray-200 dark:hover:text-indigo-500"
        >
          {{ advert.title }}
        </Link>
        <span class="text-lg font-bold text-green-600">
          {{ advert.price }} {{ advert.currency }} грн.
        </span>
      </div>
      <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ advert.region.name }} - {{ getDateFormatFromLocale(advert.created_at) }}
      </div>
      <p class="text-gray-600 text-sm dark:text-gray-300">
        {{ truncateContent(advert.content, 100) }}
      </p>
      <div class="flex justify-between items-center mt-4">
        <button @click="toggleLike(advert)">
          <HeartIcon
            v-if="!advert.is_favorited"
            class="w-6 h-6 text-gray-400 hover:text-red-400 transition"
          />
          <HeartSolidIcon
            v-else
            class="w-6 h-6 text-red-500"
          />
        </button>
        <Link
          :href="route('adverts.show', advert.id)"
          class="text-blue-600 hover:underline dark:text-blue-500"
        >
          {{ $t('more.details') }}
        </Link>
      </div>
    </div>
  </div>
</template>
