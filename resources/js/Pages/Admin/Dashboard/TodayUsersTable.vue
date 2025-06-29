<script setup>
import { getTimeFormatFromLocale } from '@/helpers.js';

const props = defineProps({
  users: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-semibold text-gray-900">
        Нові користувачі сьогодні
      </h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Користувач
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Час
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="user in users"
            :key="user.id"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <img
                  v-if="user.avatar_url"
                  :src="user.avatar_url"
                  :alt="user.name"
                  class="w-8 h-8 rounded-full mr-3"
                >
                <div
                  v-else
                  class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center"
                >
                  <span class="text-xs text-gray-600">{{ user.name.charAt(0).toUpperCase() }}</span>
                </div>
                <span class="text-sm font-medium text-gray-900">{{ user.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ getTimeFormatFromLocale(user.created_at) }}
            </td>
          </tr>
          <tr v-if="users.length === 0">
            <td
              colspan="3"
              class="px-6 py-4 text-center text-gray-500"
            >
              Сьогодні нових користувачів немає
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped></style>
