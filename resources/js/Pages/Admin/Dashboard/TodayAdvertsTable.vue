<script setup>
import { getTimeFormatFromLocale } from '@/helpers.js';
import { getAdvertStatusClass } from '@/Pages/Admin/Dashboard/helpers.js';

const props = defineProps({
  adverts: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-semibold text-gray-900">
        Нові оголошення сьогодні
      </h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Назва
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Автор
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Ціна
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Статус
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Час
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="advert in adverts"
            :key="advert.id"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span class="text-sm font-medium text-gray-900">{{ advert.title }}</span>
                <span
                  v-if="advert.premium"
                  class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full"
                >
                  Premium
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ advert.user?.name || 'Невідомо' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              ₴{{ advert.price }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 text-xs font-semibold rounded-full"
                :class="getAdvertStatusClass(advert.status)"
              >
                {{ advert.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ getTimeFormatFromLocale(advert.created_at) }}
            </td>
          </tr>
          <tr v-if="adverts.length === 0">
            <td
              colspan="5"
              class="px-6 py-4 text-center text-gray-500"
            >
              Сьогодні нових оголошень немає
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped></style>
