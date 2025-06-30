<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import { useI18n } from 'vue-i18n';
import { getDateFormatFromLocale, getFullPathForImage } from '@/helpers.js';
import { route } from 'ziggy-js';
import PdfModal from '@/Components/PdfModal.vue';
import { ref } from 'vue';
const orders = computed(() => usePage().props.orders.data);
const { t } = useI18n();
const pagination = computed(() => usePage().props.orders);
const pdfVisible = ref(false);
const currentPdfUrl = ref(null);

const showPdf = (orderId) => {
  currentPdfUrl.value = route('admin.adverts.orders.receipt', orderId);
  pdfVisible.value = true;
};
const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'advert', value: t('advert'), sortable: true },
  { key: 'service_type', value: t('service.type') },
  { key: 'price', value: t('price') },
  { key: 'payment_method', value: t('payment.method') },
  { key: 'status', value: t('status') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
  { key: 'actions', value: t('actions') },
];

const routes = [
  { key: 'index', value: 'admin.adverts.orders.index' },
  { key: 'search', value: 'admin.adverts.orders.search' },
];
</script>

<template>
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-900 dark:text-gray-200"
        >
          <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
              {{ t('log.of.activities') }}
            </h1>

            <Grid
              :items="orders"
              :pagination="pagination"
              :headings="headings"
              :routes="routes"
            >
              <template #column-advert="{ row }">
                <div class="flex gap-2 font-normal">
                  <div class="relative w-20">
                    <img
                      :src="getFullPathForImage(row.advert?.first_photo?.file)"
                      :alt="row.advert.title"
                      class="rounded"
                    >
                  </div>
                  <div class="text-sm flex justify-center items-center">
                    <Link :href="route('adverts.show', row.advert.id)">
                      <span class="">{{ row.advert.title }}</span>
                    </Link>
                  </div>
                </div>
              </template>
              <template #column-created_at="{ row }">
                {{ getDateFormatFromLocale(row.created_at) }}
              </template>
              <template #column-updated_at="{ row }">
                {{ getDateFormatFromLocale(row.updated_at) }}
              </template>
              <template #column-actions="{ row }">
                <button
                  class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700"
                  @click="showPdf(row.id)"
                >
                  {{ t('View Receipt') }}
                </button>
              </template>
            </Grid>
            <PdfModal
              :visible="pdfVisible"
              :pdf-url="currentPdfUrl"
              @close="pdfVisible = false"
            />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
