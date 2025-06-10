<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import { useI18n } from 'vue-i18n';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';
import RefreshIcon from '@/Components/Icon/RefreshIcon.vue';
import { getDateFormatFromLocale } from '@/helpers.js';
const { t } = useI18n();
const tickets = computed(() => usePage().props.tickets.data);
const pagination = computed(() => usePage().props.tickets);
console.log(tickets, 'tickets');
const isEditModalOpen = ref(false);
const showForbidden = ref(false);
const errorForbidden = ref(false);
const selectedUser = ref(null);

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'author', value: t('author'), sortable: true, highlight: true },
  { key: 'subject', value: t('subject'), sortable: true, highlight: true },
  { key: 'content', value: t('content'), sortable: true, highlight: true },
  { key: 'status', value: t('status') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
  { key: 'actions', value: t('actions') },
];

const routes = [
  { key: 'index', value: 'admin.tickets.index' },
  { key: 'search', value: 'admin.tickets.search' },
];

const openEditModal = async (id) => {
  try {
    const response = await axios.get(route('admin.users.edit', id));
    if (response.status === 200) {
      selectedUser.value = response.data;
      isEditModalOpen.value = true;
    }
  } catch (error) {
    showForbidden.value = true;
    errorForbidden.value = error.response;
  }
};

const deleteUser = (id) => {
  if (confirm('Ви впевнені, що хочете видалити цього користувача?')) {
    router.delete(route('admin.users.destroy', id), {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.users.index')),
    });
  }
};

const restoreUser = (id) => {
  router.put(
    route('admin.users.restore', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.users.index')),
    }
  );
};
</script>

<template>
  <Head :title="t('tickets')" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-900">
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4 dark:text-gray-50">
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
              <template #column-content="{ row }">
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
              <template #column-created_at="{ row }">
                {{ getDateFormatFromLocale(row.created_at) }}
              </template>
              <template #column-updated_at="{ row }">
                {{ getDateFormatFromLocale(row.updated_at) }}
              </template>
              <template #column-actions="{ row }">
                <div class="flex gap-2">
                  <div class="flex justify-end gap-4">
                    <div v-can="'user.delete'">
                      <a
                        v-if="!row.deleted_at"
                        class="text-red-600 hover:text-red-900 cursor-pointer"
                        @click.prevent="deleteUser(row.id)"
                      >
                        <TrashIcon />
                      </a>
                      <a
                        v-else
                        class="text-green-600 hover:text-green-900 cursor-pointer"
                        @click.prevent="restoreUser(row.id)"
                      >
                        <RefreshIcon />
                      </a>
                    </div>
                    <div v-can="'user.edit'">
                      <a
                        v-if="!row.deleted_at"
                        class="text-blue-600 hover:text-blue-900 cursor-pointer"
                        @click.prevent="openEditModal(row.id)"
                      >
                        <PencilIcon />
                      </a>
                    </div>
                  </div>
                </div>
              </template>
            </Grid>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
