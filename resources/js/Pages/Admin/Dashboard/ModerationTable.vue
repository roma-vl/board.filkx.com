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

const activate = async () => {
  router.post(route('admin.adverts.actions.moderation.active', { advert: props.advert.id }), {
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};

const rejectAdvert = () => {
  isRejectModalOpen.value = true;
  advertId.value = props.advert.id;
};
</script>

<template>
  <div class="mb-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900">
        Оголошення на модерації за {{ today }}
      </h3>
      <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
        {{ adverts.length }} очікує
      </span>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              ID
            </th>
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
              Дата
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Дії
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="advert in adverts"
            :key="advert.id"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              #{{ advert.id }}
            </td>
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
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ getDateFormatFromLocale(advert.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button
                  class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
                  @click="activate"
                >
                  {{ $t('publish') }}
                </button>
                <button
                  class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
                  @click="rejectAdvert"
                >
                  {{ $t('reject') }}
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="adverts.length === 0">
            <td
              colspan="6"
              class="px-6 py-4 text-center text-gray-500"
            >
              <div class="flex flex-col items-center">
                <svg
                  class="w-12 h-12 text-gray-300 mb-2"
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
                <p>Всі оголошення перевірено!</p>
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
