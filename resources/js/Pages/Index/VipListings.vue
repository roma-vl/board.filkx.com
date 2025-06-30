<script setup>
import ListingsGrid from './ListingsGrid.vue';
const props = defineProps({
  vip: Array,
});
</script>

<template>
  <section class="bg-white p-6 dark:bg-gray-800 rounded-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-300">
      {{ $t('vip.announcement') }}
    </h2>
    <ListingsGrid :listings="vip" />
  </section>
</template>
