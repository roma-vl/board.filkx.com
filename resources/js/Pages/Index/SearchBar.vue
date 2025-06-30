<script setup>
import { ref } from 'vue';
import SearchInput from '@/Components/Search/SearchInput.vue';
import LocationSelector from '@/Components/Search/LocationSelector.vue';
import { useSearch } from '@/composables/useSearch.js';

const { searchQuery, cityIdSearchQuery, search } = useSearch();

const emit = defineEmits(['search', 'select-city']);

const onSearchClick = () => {
  search();
  emit('search', searchQuery.value);
};

const onCitySelect = (slug) => {
  cityIdSearchQuery.value = slug;
  emit('select-city', slug);
};
</script>

<template>
  <div
    class="flex flex-col md:flex-row items-center gap-6 bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 p-6 rounded-3xl shadow-2xl border border-purple-200/30 dark:bg-gradient-to-br dark:from-gray-900 dark:gray-800 dark:gray-700"
  >
    <div class="flex flex-col items-center w-4/6">
      <SearchInput
        v-model="searchQuery"
        @select-suggestion="emit('search', $event)"
      />
    </div>
    <div class="flex flex-col md:flex-row items-center">
      <LocationSelector
        v-model="cityIdSearchQuery"
        class="w-full md:rounded-lg"
        @select-city="onCitySelect"
      />
    </div>
    <div class="items-center w-1/6">
      <button
        class="w-full md:rounded-lg px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-semibold hover:scale-105 active:scale-95 transition-transform duration-150 shadow-lg"
        @click="onSearchClick"
      >
        {{ $t('search.button') }}
      </button>
    </div>
  </div>
</template>
<style scoped>
nav,
header,
main,
.parent-containers {
  overflow: visible !important; /* Якщо було hidden або auto, змінити */
  position: relative; /* Створити локальний stacking context */
}
</style>
