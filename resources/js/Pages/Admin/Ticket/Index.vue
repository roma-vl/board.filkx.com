<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const tickets = computed(() => usePage().props.tickets.data);
const flash = computed(() => usePage().props.flash);
const pagination = computed(() => usePage().props.tickets);

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'author', value: t('author'), sortable: true, highlight: true },
  { key: 'subject', value: t('subject'), sortable: true, highlight: true },
  { key: 'content', value: t('content'), sortable: true, highlight: true },
  { key: 'status', value: t('status') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
];

const routes = [
  { key: 'index', value: 'admin.users.index' },
  { key: 'search', value: 'admin.users.search' },
];
</script>

<template>
  <Head :title="t('tickets')" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <FlashMessage :flash="flash" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                {{ t('tickets') }}
              </h2>
            </div>
            <Grid
              :items="tickets"
              :pagination="pagination"
              :headings="headings"
              :routes="routes"
            >
              <template #column-author="{ row }">
                <a class="text-sm hover:underline cursor-pointer">
                  {{ row.user.name }} {{ row.user.first_name }}
                </a>
              </template>
              <template #column-subject="{ row }">
                <a
                  :href="route('account.tickets.show', row.id)"
                  class="text-sm hover:underline cursor-pointer"
                >
                  {{ row.subject }}
                </a>
              </template>
              <template #column-status="{ row }">
                <span
                  class="inline-block px-4 py-1 rounded-full text font-semibold"
                  :class="{
                    'bg-green-100 text-green-800': row.status === 'open',
                    'bg-green-200 text-green-700': row.status === 'processing',
                    'bg-yellow-100 text-yellow-800': row.status === 'pending',
                    'bg-red-100 text-red-800': row.status === 'closed',
                  }"
                >
                  {{ t(row.status) }}
                </span>
              </template>
            </Grid>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
