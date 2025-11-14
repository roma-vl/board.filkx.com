<script setup>
import { getDateFormatFromLocale } from '@/helpers.js';
import { router } from '@inertiajs/vue3';
import PdfModal from '@/Components/PdfModal.vue';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const props = defineProps({
  orders: {
    type: Object,
    default: () => ({}),
  },
  routes: {
    type: Object,
    default: () => ({}),
  },
});
const pdfVisible = ref(false);
const currentPdfUrl = ref(null);

const showPdf = (orderId) => {
  currentPdfUrl.value = route(props.routes.receipt, orderId);
  pdfVisible.value = true;
};
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-4 dark:bg-gray-800">
    <div
      v-if="props.orders.data.length"
      class="divide-y divide-gray-200 dark:divide-gray-700"
    >
      <div
        v-for="order in orders.data"
        :key="order.id"
        class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition"
      >
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
          <!-- Left Block -->
          <div class="flex-1">
            <a
              :href="route('adverts.show', order.advert.id)"
              class="block group"
            >
              <h3
                class="text-lg font-bold text-gray-800 group-hover:text-violet-600 transition-colors dark:text-gray-200"
              >
                {{ order.advert.title }}
              </h3>
            </a>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              {{ $t('Created At') }}: {{ getDateFormatFromLocale(order.created_at) }}
            </p>

            <div class="mt-4 text-sm text-gray-700 dark:text-gray-300 space-y-1">
              <p>
                <span class="font-semibold">{{ $t('Order') }}:</span> #{{ order.id }}
              </p>
              <p>
                <span class="font-semibold">{{ $t('Payment Method') }}:</span>
                {{ order.payment_method }}
              </p>
              <p>
                <span class="font-semibold">{{ $t('Total Price') }}:</span> {{ order.price }} грн
              </p>
            </div>

            <!-- Items -->
            <div class="mt-4">
              <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                {{ $t('Services included') }}:
              </p>
              <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300">
                <li
                  v-for="item in order.items"
                  :key="item.id"
                >
                  {{ $t(item.service_type) }} — {{ parseFloat(item.price).toFixed(2) }} грн
                </li>
              </ul>
            </div>
          </div>

          <!-- Right Block -->
          <div class="flex-shrink-0 flex items-start sm:items-center mt-4 sm:mt-0">
            <span
              class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wide"
              :class="{
                'bg-green-100 text-green-800': order.status === 'paid',
                'bg-green-100 text-green-400': order.status === 'approved',
                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                'bg-yellow-200 text-yellow-700': order.status === 'processing',
                'bg-red-100 text-red-800': order.status === 'canceled',
                'bg-red-100 text-red-700': order.status === 'closed',
              }"
            >
              {{ $t(order.status) }}
            </span>
          </div>
          <button
            class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700"
            @click="showPdf(order.id)"
          >
            {{ t('View Receipt') }}
          </button>
        </div>
      </div>
    </div>

    <p
      v-else
      class="py-12 text-center text-gray-500 text-lg dark:text-gray-400"
    >
      {{ $t('You have no orders') }}.
    </p>
  </div>

  <PdfModal
    :visible="pdfVisible"
    :pdf-url="currentPdfUrl"
    @close="pdfVisible = false"
  />
</template>
