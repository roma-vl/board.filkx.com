<script setup>
import ListingsGrid from './ListingsGrid.vue';

const props = defineProps({
  vip: Array,
});
</script>

<template>
  <section class="mt-12">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ $t('vip.announcement') }}
      </h2>
    </div>
    <ListingsGrid :listings="vip" />
  </section>
</template>
