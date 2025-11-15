<script setup>
import { getDateFormatFromLocale } from '@/helpers.js';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Reject from '@/Pages/Admin/Banner/Actions/Reject.vue';

const props = defineProps({
  adverts: {
    type: Array,
    default: () => [],
  },
  today: String,
});

const isRejectModalOpen = ref(false);
const advertId = ref(null);

const activate = async (advertId) => {
  router.post(route('admin.adverts.actions.moderation.active', { advert: advertId }), {
    onSuccess: () => router.reload(),
  });
};

const rejectAdvert = (id) => {
  isRejectModalOpen.value = true;
  advertId.value = id;
};
</script>

<template>
  <div
    class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
  >
    <div
      class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
    >
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
          Оголошення на модерації за {{ today }}
        </h3>
        <span
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 mt-1"
        >
          {{ adverts.length }} очікує
        </span>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              ID
            </th>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              Назва
            </th>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              Автор
            </th>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              Ціна
            </th>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              Дата
            </th>
            <th
              scope="col"
              class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
            >
              Дії
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr
            v-for="advert in adverts"
            :key="advert.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
          >
            <td
              class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
            >
              #{{ advert.id }}
            </td>
            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span class="text-sm font-medium text-gray-900 dark:text-white">{{
                  advert.title
                }}</span>
                <span
                  v-if="advert.premium"
                  class="ml-2 px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full"
                >
                  Premium
                </span>
              </div>
            </td>
            <td
              class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
            >
              {{ advert.user?.name || 'Невідомо' }}
            </td>
            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
              ₴{{ advert.price }}
            </td>
            <td
              class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
            >
              {{ getDateFormatFromLocale(advert.created_at) }}
            </td>
            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex flex-col sm:flex-row gap-2 sm:gap-2">
                <button
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                  @click="activate(advert.id)"
                >
                  {{ $t('publish') }}
                </button>
                <button
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                  @click="rejectAdvert(advert.id)"
                >
                  {{ $t('reject') }}
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="adverts.length === 0">
            <td
              colspan="6"
              class="px-4 sm:px-6 py-12 text-center"
            >
              <div class="flex flex-col items-center">
                <svg
                  class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                  Всі оголошення перевірено!
                </p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <Modal
    :show="isRejectModalOpen"
    max-width="2xl"
    @close="isRejectModalOpen = false"
  >
    <Reject
      :advert-id="advertId"
      @reject-created="isRejectModalOpen = false"
    />
  </Modal>
</template>

<style scoped></style>
