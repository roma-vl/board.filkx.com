<script setup>
import { ref, watch } from 'vue';
import CategoryDropdownItem from '@/Components/CategoryDropdownItem.vue';

const props = defineProps({
  modelValue: {
    type: [Number, String],
    default: null,
  },
  categoryFilters: {
    type: Array,
    default: () => [],
  },
});
const emit = defineEmits(['update:modelValue', 'toggle']);

const isOpen = ref(false);
const selectedCategory = ref(null);

function toggleDropdown() {
  isOpen.value = !isOpen.value;
}

function selectCategory(category) {
  selectedCategory.value = category;
  emit('update:modelValue', category.id);
  emit('toggle', 'open');
  isOpen.value = false;
}

function findCategoryById(categories, id) {
  for (const category of categories) {
    if (category.id === id) return category;
    if (category.children?.length) {
      const found = findCategoryById(category.children, id);
      if (found) return found;
    }
  }
  return null;
}

watch(
  () => props.modelValue,
  (newVal) => {
    selectedCategory.value = findCategoryById(props.categoryFilters, Number(newVal));
  },
  { immediate: true }
);
</script>

<template>
  <div class="relative inline-block w-full">
    <div
      class="cursor-pointer px-4 py-2 rounded bg-white shadow dark:bg-gray-700 dark:hover:bg-gray-800"
      @click="toggleDropdown"
    >
      {{ selectedCategory?.name || $t('select.category') }}
    </div>

    <div
      v-if="isOpen"
      class="absolute left-0 top-full mt-2 w-full z-50 bg-white shadow-lg rounded-lg max-h-96 overflow-y-auto dark:bg-gray-700"
    >
      <CategoryDropdownItem
        v-for="category in categoryFilters"
        :key="category.id"
        :category="category"
        @select="selectCategory"
      />
    </div>
  </div>
</template>
