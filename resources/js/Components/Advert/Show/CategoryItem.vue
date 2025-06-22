<template>
  <li class="pl-2">
    <div
      class="flex justify-between items-center py-1 px-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition"
    >
      <!-- Назва категорії — клік для пошуку -->
      <div class="flex">
        <span
          class="cursor-pointer text-blue-600 hover:underline dark:text-blue-400 p-1"
          @click="$emit('select', category.id)"
        >
          <span>{{ category.name }}</span>
        </span>
        <span
          class="text text-gray-500 dark:text-gray-800 flex-row bg-purple-500/50 dark:bg-purple-500/50 rounded-lg p-1 ml-3"
        >({{ category.count }})</span>
      </div>
      <!-- Іконка розгортання -->
      <button
        v-if="category.children.length"
        class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition pr-2"
        @click.stop="$emit('toggle', category)"
      >
        <svg
          v-if="!category.expanded"
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 12H4"
          />
        </svg>
      </button>
    </div>

    <!-- Дочірні -->
    <transition name="fade">
      <ul
        v-if="category.expanded"
        class="ml-4 mt-1 space-y-1"
      >
        <CategoryItem
          v-for="child in category.children"
          :key="child.id"
          :category="child"
          @toggle="$emit('toggle', child)"
          @select="$emit('select', $event)"
        />
      </ul>
    </transition>
  </li>
</template>

<script setup>
defineProps({
  category: Object,
});
</script>
