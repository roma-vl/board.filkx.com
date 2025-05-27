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
  if (!event.target.closest('.search-container')) {
    showSuggestions.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div class="relative w-full search-container">
    <input
      v-model="inputValue"
      type="text"
      :placeholder="$t('search.button')+ '...'"
      class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
      @focus="showSuggestions = true"
    >
    <ul
      v-if="showSuggestions && (searchHistory.length || searchRecommendations.length)"
      class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10"
    >
      <template v-if="searchHistory.length">
        <li class="text-sm text-gray-400 uppercase p-1 pl-4">
          {{$t('You recently searched')}}
        </li>
        <li
          v-for="(suggestion, index) in searchHistory"
          :key="'history-' + index"
          class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
          @click="selectSuggestion(suggestion)"
        >
          {{ suggestion }}
          <button
            class="text-red-500 text-lg hover:text-red-700 transition duration-200"
            @click.stop="removeSuggestion(index)"
          >
            ×
          </button>
        </li>
      </template>

      <template v-if="searchRecommendations.length">
        <li class="text-sm text-gray-400 uppercase p-1 pl-4">
           {{$t('recommendation')}}
        </li>
        <li
          v-for="(suggestion, index) in searchRecommendations"
          :key="'recommendation-' + index"
          class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
          @click="selectSuggestion(suggestion)"
        >
          {{ suggestion }}
        </li>
      </template>
    </ul>
  </div>
</template>
