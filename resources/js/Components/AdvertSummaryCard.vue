<!-- components/AdvertSummaryCard.vue -->
<script setup>
import { getFullPathForImage } from '@/helpers.js';

const props = defineProps({
  advert: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div
    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4 shadow space-y-4"
  >
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
      Ваше оголошення
    </h2>

    <div class="flex gap-4">
      <div class="shrink-0">
        <img
          :src="getFullPathForImage(props.advert.photo[0].file)"
          alt="Фото оголошення"
          class="w-28 h-28 object-cover rounded-md border"
        >
      </div>
      <div class="flex flex-col justify-between">
        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-1 leading-tight">
          {{ props.advert.title }}
        </h3>
        <p class="text-lg font-bold text-green-700 dark:text-green-400">
          {{ props.advert.price }} грн
        </p>
      </div>
    </div>

    <div class="border-t pt-3 text-sm text-gray-500 dark:text-gray-400">
      <p>📌 Виберіть послуги, щоб підвищити ефективність оголошення.</p>
    </div>
  </div>
</template>
