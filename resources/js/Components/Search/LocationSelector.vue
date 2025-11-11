<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useLocationSearch } from '@/composables/useLocationSearch.js';

const {
  citySearchQuery,
  showLocationDropdown,
  selectedRegion,
  loadingRegions,
  loadingCities,
  filteredCities,
  regions,
  cities,
  resetLocation,
  fetchCities,
  searchCities,
  selectCity,
} = useLocationSearch();

const props = defineProps({
  modelValue: {
    type: [Object, String],
    default: () => '',
  },
});

const emit = defineEmits(['update:modelValue', 'select-city']);

const inputValue = ref(props.modelValue.name || '');
const dropdownRef = ref(null);

watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal?.name) {
      citySearchQuery.value = newVal.name;
      inputValue.value = newVal.slug;
    }
  },
  { immediate: true }
);

watch(inputValue, (newVal) => {
  emit('update:modelValue', newVal);
});

watch(citySearchQuery, searchCities);

defineExpose({ selectCity, citySearchQuery });

const handleClickOutside = (event) => {
  if (!event.target.closest('.location-selector-container')) {
    showLocationDropdown.value = false;
    selectedRegion.value = null;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Function to calculate dropdown position
const getDropdownPosition = () => {
  const inputElement = document.querySelector('.location-selector-container input');
  if (!inputElement) return { top: '0px', left: '0px' };

  const rect = inputElement.getBoundingClientRect();
  return {
    top: `${rect.bottom + window.scrollY}px`,
    left: `${rect.left + window.scrollX}px`,
    width: `${rect.width}px`,
  };
};
</script>

<template>
  <div class="relative w-full location-selector-container">
    <div class="relative">
      <input
        v-model="citySearchQuery"
        type="text"
        :placeholder="$t('Select region')"
        class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white transition-colors duration-200"
        @focus="resetLocation"
      >
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg
          class="h-5 w-5 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
          />
        </svg>
      </div>
    </div>

    <Teleport to="body">
      <div
        v-if="showLocationDropdown"
        ref="dropdownRef"
        class="absolute left-0 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-[100] max-h-80 overflow-y-auto dark:bg-gray-800 dark:border-gray-700"
        :style="getDropdownPosition()"
      >
        <ul v-if="filteredCities.length">
          <li
            v-for="city in filteredCities"
            :key="city.id"
            class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750 dark:text-gray-300"
            role="option"
            @click="$emit('select-city', selectCity(city))"
          >
            {{ city.name }}
          </li>
        </ul>
        <div v-else>
          <ul v-if="regions.length && !cities.length">
            <li
              v-if="loadingRegions"
              class="px-4 py-3 text-gray-500 dark:text-gray-400"
            >
              {{ $t('download') }}...
            </li>
            <li
              v-for="region in regions"
              :key="region.id"
              class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750 dark:text-gray-300"
              role="option"
              @click="fetchCities(region.id)"
            >
              {{ region.name }}
            </li>
          </ul>

          <ul v-else>
            <li
              v-if="loadingCities"
              class="px-4 py-3 text-gray-500 dark:text-gray-400"
            >
              {{ $t('download') }}...
            </li>
            <li
              class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750 dark:text-gray-300"
              role="option"
              @click="resetLocation"
            >
              <span class="flex items-center">
                <svg
                  class="h-4 w-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                  />
                </svg>
                {{ $t('back') }}
              </span>
            </li>
            <!-- Безпечна перевірка selectedRegion перед доступом до .name -->
            <li
              v-if="selectedRegion"
              class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750 dark:text-gray-300"
              role="option"
              @click="$emit('select-city', selectCity(selectedRegion))"
            >
              <span class="font-medium">{{ selectedRegion.name }}</span>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $t('The whole region') }}
              </p>
            </li>
            <li
              class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 dark:bg-gray-750 dark:text-gray-400"
            >
              {{ $t('Select region') }}
            </li>
            <li
              v-for="city in cities"
              :key="city.id"
              class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750 dark:text-gray-300"
              role="option"
              @click="$emit('select-city', selectCity(city))"
            >
              {{ city.name }}
            </li>
          </ul>
        </div>
      </div>
    </Teleport>
  </div>
</template>
