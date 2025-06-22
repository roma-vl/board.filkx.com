<script setup>
import { getDateFormatFromLocale, getFullPathForImage, truncateContent } from '@/helpers.js';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
  advert: {
    type: Object,
    default: () => ({}),
  },
  viewMode: {
    type: String,
    default: '',
  },
});

const heartAnimate = ref(false);

const toggleLike = (advert) => {
  if (!advert.is_favorited) {
    heartAnimate.value = true;
    setTimeout(() => (heartAnimate.value = false), 400); // reset after anim
    router.post(route('account.favorites.add', { advert: advert.id }));
  } else {
    router.delete(route('account.favorites.remove', { advert: advert.id }));
  }
  advert.is_favorited = !advert.is_favorited;
};
</script>

<template>
  <div
    class="flex bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-2xl transition-all duration-300 dark:bg-gray-800 dark:border-gray-900"
  >
    <img
      class="object-cover w-48 h-48 hover:scale-105 transition-transform duration-300"
      :src="getFullPathForImage(advert.first_photo?.file)"
      :alt="advert.title"
    >
    <div class="p-5 flex-1 space-y-2">
      <div class="">
        <div class="flex justify-between items-start pb-3">
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

        <p class="text-gray-700 text-sm dark:text-gray-300">
          {{ truncateContent(advert.content, 200) }}
        </p>
      </div>
      <div
        class="flex justify-between items-center pt-3 mt-auto border-t border-gray-100 dark:border-gray-700"
      >
        <div class="text-sm text-gray-500 dark:text-gray-400">
          {{ advert.region.name }} - {{ getDateFormatFromLocale(advert.created_at) }}
        </div>
        <button @click="toggleLike(advert)">
          <span :class="['inline-block', heartAnimate ? 'heart-animate' : '']">
            <HeartIcon
              v-if="!advert.is_favorited"
              class="w-6 h-6 text-gray-400 hover:text-red-400 transition"
            />
            <HeartSolidIcon
              v-else
              class="w-6 h-6 text-red-500 transition"
            />
          </span>
        </button>
      </div>
    </div>
  </div>
</template>
<style scoped>
@keyframes heart-beat {
  0%,
  100% {
    transform: scale(1);
  }
  25% {
    transform: scale(1.3);
  }
  50% {
    transform: scale(0.9);
  }
  75% {
    transform: scale(1.1);
  }
}
</style>
