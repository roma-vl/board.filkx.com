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
  <div class="space-y-4">
    <div class="flex flex-col md:flex-row gap-4">
      <div class="flex-1">
        <SearchInput
          v-model="searchQuery"
          @select-suggestion="emit('search', $event)"
        />
      </div>
      <div class="w-full md:w-64">
        <LocationSelector
          v-model="cityIdSearchQuery"
          @select-city="onCitySelect"
        />
      </div>
      <div class="w-full md:w-32">
        <button
          type="button"
          class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all duration-200"
          @click="onSearchClick"
        >
          {{ $t('search.button') }}
        </button>
      </div>
    </div>
  </div>
</template>
