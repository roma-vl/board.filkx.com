<script setup>
import ListingsGrid from './ListingsGrid.vue';
const props = defineProps({
  news: Array,
});
</script>

<template>
  <section class="bg-white p-6 mt-10 dark:bg-gray-800 rounded-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-300">
      {{ $t('last.announcement') }}
    </h2>
    <ListingsGrid :listings="news" />
  </section>
</template>
