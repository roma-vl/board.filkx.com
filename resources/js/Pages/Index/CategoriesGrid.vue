<script setup>
import { ref, computed } from 'vue';
import { fullPath } from '@/helpers.js';

const props = defineProps({
  categories: Array,
});

const openCategory = ref(null);

const toggleCategory = (categoryId) => {
  openCategory.value = openCategory.value === categoryId ? null : categoryId;
};

const selectedCategory = computed(() => {
  return props.categories.find((c) => c.id === openCategory.value) || null;
});

const subCategories = computed(() => {
  return selectedCategory.value ? selectedCategory.value.children : [];
});
</script>

<template>
  <section class="my-12">
    <div class="pb-4 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
        {{ $t('divided.into.services') }}
      </h2>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
      <template
        v-for="category in categories"
        :key="category.id"
      >
        <div class="text-center">
          <button
            type="button"
            class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 w-full h-full flex flex-col justify-center items-center border border-gray-100 dark:border-gray-700"
            :aria-expanded="openCategory === category.id"
            :aria-controls="`subcategories-${category.id}`"
            @click="toggleCategory(category.id)"
          >
            <img
              src="https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png"
              :alt="category.name"
              class="w-10 h-10 object-contain mb-2"
              loading="lazy"
            >
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
              {{ category.name }}
            </span>
          </button>
        </div>
      </template>
    </div>

    <!-- Subcategories Dropdown -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 transform scale-95"
      enter-to-class="opacity-100 transform scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 transform scale-100"
      leave-to-class="opacity-0 transform scale-95"
    >
      <div
        v-if="openCategory && subCategories.length > 0"
        :id="`subcategories-${openCategory}`"
        class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700"
        role="region"
        :aria-labelledby="`category-${openCategory}-label`"
      >
        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-4">
          {{ $t('look.at.all.the.announcement_in') }}
          <a
            :href="fullPath() + '/' + selectedCategory.slug"
            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
          >
            {{ selectedCategory.name }}
          </a>
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
          <template
            v-for="subCategory in subCategories"
            :key="subCategory.id"
          >
            <a
              :href="fullPath() + '/' + selectedCategory.slug + '/' + subCategory.slug"
              class="text-sm text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              {{ subCategory.name }}
            </a>
          </template>
        </div>
      </div>
    </transition>
  </section>
</template>
