<script setup>
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import { Link, router } from '@inertiajs/vue3';
import { getFullPathForImage } from '@/helpers.js';

const props = defineProps({
  listings: Array,
});

const emit = defineEmits(['toggle-like']);

const toggleLike = (advert) => {
  if (advert.is_favorited) {
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
  advert.is_favorited = !advert.is_favorited;
  emit('toggle-like', advert);
};
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <template
      v-for="listing in listings"
      :key="listing.id"
    >
      <div
        class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 bg-white dark:bg-gray-700"
      >
        <img
          :src="getFullPathForImage(listing.first_photo?.file)"
          :alt="listing.title"
          class="w-full h-48 object-cover"
        >
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-300">
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
              class="text-blue-600 hover:underline dark:text-blue-500"
            >
              {{ $t('more.details') }}
            </Link>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
