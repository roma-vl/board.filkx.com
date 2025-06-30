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
  <section class="my-10">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-300">
      {{ $t('divided.into.services') }}
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6">
      <template
        v-for="category in categories"
        :key="category.id"
      >
        <div class="text-center">
          <button
            class="p-4 dark:bg-gray-800 dark:hover:bg-gray-600 rounded-2xl bg-white hover:bg-gray-100 shadow-lg w-full h-full flex flex-col justify-center items-center"
            @click="toggleCategory(category.id)"
          >
            <img
              src="https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png"
              :alt="category.name"
              class="w-12 h-12"
            >
            <span class="text-sm mt-2 font-medium text-gray-700 dark:text-gray-100">
              {{ category.name }}
            </span>
          </button>
        </div>
      </template>

      <transition name="fade">
        <div
          v-if="openCategory && subCategories.length > 0"
          class="col-span-full bg-white rounded-2xl shadow-lg p-6 dark:bg-gray-800"
        >
          <p class="text-sm font-semibold mb-4 dark:text-gray-400">
            {{ $t('look.at.all.the.announcement.in') }}
            <a
              :href="fullPath() + '/' + selectedCategory.slug"
              class="text-blue-600 hover:underline dark:text-gray-300"
            >
              {{ selectedCategory.name }}
            </a>
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <template
              v-for="subCategory in subCategories"
              :key="subCategory.id"
            >
              <a
                :href="fullPath() + '/' + selectedCategory.slug + '/' + subCategory.slug"
                class="text-sm text-gray-700 hover:underline dark:text-gray-100 dark:hover:bg-gray-700 p-2 rounded shadow hover:bg-gray-100"
              >
                {{ subCategory.name }}
              </a>
            </template>
          </div>
        </div>
      </transition>
    </div>
  </section>
</template>
