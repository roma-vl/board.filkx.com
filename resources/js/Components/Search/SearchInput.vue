<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useSearch } from '@/composables/useSearch.js';

const { searchQuery, showSuggestions, searchHistory, searchRecommendations, removeSuggestion } =
  useSearch();

defineExpose({ searchQuery });

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue', 'select-suggestion']);

const inputValue = ref(props.modelValue);
const dropdownRef = ref(null);

watch(
  () => props.modelValue,
  (newVal) => {
    inputValue.value = newVal;
  }
);

watch(inputValue, (newVal) => {
  emit('update:modelValue', newVal);
});

const selectSuggestion = (text) => {
  emit('select-suggestion', text);
  emit('update:modelValue', text);
  showSuggestions.value = false;
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.search-input-container')) {
    showSuggestions.value = false;
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
  const inputElement = document.querySelector('.search-input-container input');
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
  <div class="relative w-full search-input-container">
    <div class="relative">
      <input
        v-model="inputValue"
        type="text"
        :placeholder="$t('search.button') + '...'"
        class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white transition-colors duration-200"
        @focus="showSuggestions = true"
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
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
      </div>
    </div>

    <Teleport to="body">
      <ul
        v-if="showSuggestions && (searchHistory.length || searchRecommendations.length)"
        ref="dropdownRef"
        class="absolute left-0 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-[100] divide-y divide-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700"
        :style="getDropdownPosition()"
      >
        <template v-if="searchHistory.length">
          <li
            class="text-xs font-semibold text-gray-500 uppercase tracking-wider p-3 bg-gray-50 dark:bg-gray-750 dark:text-gray-400"
          >
            {{ $t('You recently searched') }}
          </li>
          <li
            v-for="(suggestion, index) in searchHistory"
            :key="'history-' + index"
            class="flex justify-between items-center px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750"
            role="option"
            @click="selectSuggestion(suggestion)"
          >
            <span class="text-gray-700 dark:text-gray-300">{{ suggestion }}</span>
            <button
              type="button"
              class="text-gray-400 hover:text-red-500 transition-colors duration-150"
              :aria-label="$t('Remove from history')"
              @click.stop="removeSuggestion(index)"
            >
              <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </li>
        </template>

        <template v-if="searchRecommendations.length">
          <li
            class="text-xs font-semibold text-gray-500 uppercase tracking-wider p-3 bg-gray-50 dark:bg-gray-750 dark:text-gray-400"
          >
            {{ $t('recommendation') }}
          </li>
          <li
            v-for="(suggestion, index) in searchRecommendations"
            :key="'recommendation-' + index"
            class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-750"
            role="option"
            @click="selectSuggestion(suggestion)"
          >
            <span class="text-gray-700 dark:text-gray-300">{{ suggestion }}</span>
          </li>
        </template>
      </ul>
    </Teleport>
  </div>
</template>
