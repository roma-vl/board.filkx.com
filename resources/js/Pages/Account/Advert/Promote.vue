<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdvertServices from '@/Components/AdvertServices.vue';
import { getFullPathForImage } from '@/helpers.js';
import AdvertSummaryCard from '@/Components/AdvertSummaryCard.vue';
const props = defineProps({
  advert: {
    type: Array,
    default: () => {},
  },
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md flex"
        >
          <div class="px-4 w-2/3">
            <AdvertServices :advert="props.advert" />
          </div>
          <div class="px-4 w-1/3">
            <AdvertSummaryCard :advert="props.advert" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
