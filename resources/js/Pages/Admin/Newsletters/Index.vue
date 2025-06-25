<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useI18n } from 'vue-i18n';
import Grid from '@/Components/Grid.vue';
import { getDateFormatFromLocale } from '@/helpers.js';

const newsletters = computed(() => usePage().props.newsletters.data);
const { t } = useI18n();
const pagination = computed(() => usePage().props.newsletters);

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'title', value: t('title'), sortable: true, disabled: true },
  { key: 'scheduled_at', value: t('scheduled_at') },
  { key: 'sent', value: t('status') },
  { key: 'actions', value: t('actions') },
];

const routes = [
  { key: 'index', value: 'admin.newsletter.index' },
  { key: 'search', value: 'admin.newsletter.search' },
];
function destroy(id) {
  if (confirm('Ви впевнені?')) {
    router.delete(route('admin.newsletters.destroy', id));
  }
}
</script>

<template>
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-900 dark:text-gray-200"
        >
          <div class="p-4 space-y-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
              📬 Розсилки
            </h1>
            <div class="flex justify-end">
              <a
                v-can="'user.create'"
                class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
                :href="route('admin.newsletters.create')"
              >
                + {{ t('new.user') }}
              </a>
            </div>

            <Grid
              :items="newsletters"
              :pagination="pagination"
              :headings="headings"
              :routes="routes"
            >
              <template #column-sent="{ row }">
                <span :class="row.sent ? 'text-green-600' : 'text-yellow-600'">
                  {{ row.sent ? 'надіслано' : 'в очікуванні' }}
                </span>
              </template>
              <template #column-scheduled_at="{ row }">
                {{ getDateFormatFromLocale(row.scheduled_at) }}
              </template>
              <template #column-actions="{ row }">
                <button
                  class="text-red-600 hover:underline"
                  @click="destroy(row.id)"
                >
                  🗑
                </button>
              </template>
            </Grid>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
