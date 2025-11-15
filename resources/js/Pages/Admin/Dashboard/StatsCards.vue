```vue
<script setup>
import DashboardUsersIcon from '@/Components/Icon/DashboardUsersIcon.vue';
import DashboardShopIcon from '@/Components/Icon/DashboardShopIcon.vue';
import DashboardSalesIcon from '@/Components/Icon/DashboardSalesIcon.vue';

const props = defineProps({
  stats: Object,
});
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Користувачі -->
    <div
      class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/20 group"
    >
      <div class="flex items-center">
        <div
          class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg transition-all duration-300 group-hover:scale-105"
        >
          <DashboardUsersIcon class="w-6 h-6 text-blue-600 dark:text-blue-400" />
        </div>
        <div class="ml-4 flex-1">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Нові користувачі
          </p>
          <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">
            {{ stats.users.today }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Всього: {{ stats.users.total }}
          </p>
        </div>
      </div>
    </div>

    <!-- Замовлення -->
    <div
      class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/20 group"
    >
      <div class="flex items-center">
        <div
          class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg transition-all duration-300 group-hover:scale-105"
        >
          <DashboardShopIcon class="w-6 h-6 text-green-600 dark:text-green-400" />
        </div>
        <div class="ml-4 flex-1">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Нові замовлення
          </p>
          <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">
            {{ stats.orders.today }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Всього: ₴{{ stats.orders.revenue_today }}
          </p>
        </div>
      </div>
    </div>

    <!-- Оголошення -->
    <div
      class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/20 group"
    >
      <div class="flex items-center">
        <div
          class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg transition-all duration-300 group-hover:scale-105"
        >
          <DashboardSalesIcon class="w-6 h-6 text-purple-600 dark:text-purple-400" />
        </div>
        <div class="ml-4 flex-1">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Нові оголошення
          </p>
          <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">
            {{ stats.adverts.today }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Всього: {{ stats.adverts.total }}
          </p>
        </div>
      </div>
    </div>

    <!-- Плейсхолдер -->
    <div
      class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/20 group"
    >
      <div class="flex items-center">
        <div
          class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg transition-all duration-300 group-hover:scale-105"
        >
          <DashboardSalesIcon class="w-6 h-6 text-gray-600 dark:text-gray-400" />
        </div>
        <div class="ml-4 flex-1">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Ще щось
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
```
