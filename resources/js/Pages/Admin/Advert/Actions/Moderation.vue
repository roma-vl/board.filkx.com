<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import Grid from '@/Components/Grid.vue';
import RefreshIcon from '@/Components/Icon/RefreshIcon.vue';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Reject from '@/Pages/Admin/Advert/Actions/Reject.vue';
import CheckIcon from '@/Components/Icon/CheckIcon.vue';
import ArrowUturnIcon from '@/Components/Icon/ArrowUturnIcon.vue';
import EyeIcon from '@/Components/Icon/EyeIcon.vue';
import { useI18n } from 'vue-i18n';
import { getDateFormatFromLocale } from '@/helpers.js';
const { t } = useI18n();
const adverts_moderation = computed(() => usePage().props.adverts_moderation.data);
const pagination = computed(() => usePage().props.adverts_moderation);
const isRejectModalOpen = ref(false);
const advertId = ref(null);
const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'title', value: t('title') },
  { key: 'price', value: t('price') },
  { key: 'status', value: t('status') },
  { key: 'content', value: t('content') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
  { key: 'actions', value: t('actions'), disabled: true },
];

const routes = [
  { key: 'index', value: 'admin.adverts.moderation' },
  { key: 'search', value: 'admin.adverts.moderation.search' },
];

const restoreAdvert = (id) => {
  router.put(route('admin.adverts.actions.moderation.restore', id));
};
const activateAdvert = async (advert) => {
  router.post(route('admin.adverts.actions.moderation.active', { advert: advert }));
};

const rejectAdvert = async (id) => {
  isCreateModalOpen.value = true;
  advertId.value = id;
};

const deleteAdvert = (id) => {
  if (confirm('Ви впевнені, що хочете видалити оголошення?')) {
    router.delete(route('admin.adverts.actions.moderation.destroy', id));
  }
};
</script>

<template>
  <Head :title="t('Adverts') + ' ' + t('for.moderation')" />
  <AdminLayout>
    <div class="py-4 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px] dark:bg-gray-800">
          <h2 class="dark:text-gray-200 text-[20px] pb-4 pl-2 bold">
            {{ t('Adverts') + ' ' + t('for.moderation') }}
          </h2>
          <Grid
            :items="adverts_moderation"
            :pagination="pagination"
            :headings="headings"
            :routes="routes"
          >
            <template #column-status="{ row }">
              <span
                class="inline-block px-4 py-1 rounded-full text-xs font-semibold"
                :class="{
                  'bg-green-100 text-green-800': row.status === 'open',
                  'bg-green-200 text-green-700': row.status === 'processing',
                  'bg-green-100 text-green-700': row.status === 'moderation',
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
                  <a
                    :href="route('adverts.show', row.id)"
                    class="text-blue-600 hover:underline"
                    :title="t('view')"
                  >
                    <EyeIcon /></a>
                  <a
                    class="text-green-600 font-bold hover:text-green-900 cursor-pointer"
                    :title="t('publish')"
                    @click.prevent="activateAdvert(row.id)"
                  ><CheckIcon /></a>
                  <a
                    class="text-red-600 hover:text-red-900 cursor-pointer"
                    :title="t('cancel')"
                    @click.prevent="rejectAdvert(row.id)"
                  ><ArrowUturnIcon /></a>
                  <a
                    v-if="!row.deleted_at"
                    :href="route('cabinet.adverts.edit', row.id)"
                    class="text-blue-600 hover:text-blue-900 cursor-pointer"
                    :title="t('edit')"
                  >
                    <PencilIcon />
                  </a>
                  <a
                    v-if="!row.deleted_at"
                    class="text-red-600 hover:text-red-900 cursor-pointer"
                    :title="t('delete')"
                    @click.prevent="deleteAdvert(row.id)"
                  >
                    <TrashIcon />
                  </a>
                  <a
                    v-else
                    class="text-green-600 hover:text-green-900 cursor-pointer"
                    @click.prevent="restoreAdvert(row.id)"
                  >
                    <RefreshIcon />
                  </a>
                </div>
              </div>
            </template>
          </Grid>
        </div>
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
  </AdminLayout>
</template>
