<script setup>
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import AdvertGrid from '@/Components/Advert/Category/AdvertGrid.vue';
import AdvertList from '@/Components/Advert/Category/AdvertList.vue';

const props = defineProps({
  adverts: {
    type: Object,
    default: () => ({}),
  },
});

const viewMode = ref('grid');
const toggleViewMode = () => {
  viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};
</script>

<template>
  <div
    v-if="adverts?.data.length"
    class="space-y-6"
  >
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-300">
          🔍 {{ $t('found') }} <span class="text-indigo-600">{{ adverts.total }}</span>
          {{ $t('adverts') }}
        </h2>
        <div class="flex items-center gap-2 ml-4">
          <button
            :class="[
              'p-2 rounded-xl shadow-md transition-all duration-300 border',
              viewMode === 'grid'
                ? 'bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-lg scale-105 '
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300 hover:scale-105 dark:bg-gray-800 dark:border-gray-900 dark:text-gray-100',
            ]"
            title="Сітка"
            @click="toggleViewMode"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
              />
            </svg>
          </button>
          <button
            :class="[
              'p-2 rounded-xl shadow-md transition-all duration-300 border',
              viewMode === 'list'
                ? 'bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-lg scale-105'
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-gray-300 hover:scale-105 dark:bg-gray-800 dark:border-gray-900 dark:text-gray-100',
            ]"
            title="Список"
            @click="toggleViewMode"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>
      </div>
      <div class="text-sm text-gray-500 italic dark:text-gray-300">
        {{ $t('page') }} {{ adverts.page }} {{ $t('of') }} {{ adverts.last_page }}
      </div>
    </div>

    <div v-if="viewMode === 'grid'">
      <AdvertGrid :adverts="adverts.data" />
    </div>
    <div v-else>
      <AdvertList :adverts="adverts.data" />
    </div>

    <Pagination :pagination="adverts" />
  </div>
</template>
