<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import AvatarIcon from '@/Components/Icon/AvatarIcon.vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { getFullPathForAvatarImage } from '@/helpers.js';
const adverts = usePage().props.adverts.data;
const pagination = computed(() => usePage().props.adverts.meta);
const { t } = useI18n();

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'title', value: t('title') },
  { key: 'price', value: t('price') },
  { key: 'user', value: t('user'), sortable: true, highlight: true },
  { key: 'category', value: t('category'), sortable: true, highlight: true },
  { key: 'region', value: t('region') },
  { key: 'slug', value: t('slug') },
  { key: 'address', value: t('address') },
  { key: 'content', value: t('content') },
  { key: 'status', value: t('status') },
  { key: 'reject_reason', value: t('reject_reason') },
  { key: 'premium', value: t('premium') },
  { key: 'published_at', value: t('published_at') },
  { key: 'expires_at', value: t('expires_at') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
  { key: 'deleted_at', value: t('deleted_at') },
  { key: 'actions', value: t('actions'), disabled: true },
];
const routes = [
  { key: 'index', value: 'admin.adverts.index' },
  { key: 'search', value: 'admin.adverts.search' },
];

const isCreateModalOpen = ref(false);
const showForbidden = ref(false);
const errorForbidden = ref(false);
</script>

<template>
  <Head title="Users" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 rounded-md pt-2 dark:bg-gray-900">
        <Grid
          :items="adverts"
          :pagination="pagination"
          :headings="headings"
          :routes="routes"
        >
          <template #column-title="{ row }">
            <span
              class="font-medium cursor-pointer"
              v-html="row.title"
            />
          </template>
          <template #column-user="{ row }">
            <div class="flex gap-2 font-normal">
              <div class="relative h-10 w-10">
                <div v-if="row.user?.avatar_url">
                  <img
                    :src="getFullPathForAvatarImage(row.user?.avatar_url)"
                    :alt="row.user?.name"
                    class="rounded"
                  >
                </div>
                <div v-else>
                  <AvatarIcon />
                </div>
              </div>
              <div class="text-sm flex justify-center items-center">
                <span class="font-medium hover:underline cursor-pointer">
                  {{ row.user?.name }}</span>
              </div>
            </div>
          </template>
          <template #column-category="{ row }">
            <span
              class="font-medium cursor-pointer"
              v-html="row.category?.name"
            />
          </template>
          <template #column-region="{ row }">
            <span
              class="font-medium cursor-pointer"
              v-html="row.region?.name"
            />
          </template>
          <template #column-status="{ row }">
            <div>
              <span
                class="m-1 inline-block px-3 py-1 rounded-full text-sm font-semibold"
                :class="{
                  'bg-green-100 text-green-800': row.status === 'open',
                  'bg-green-200 text-green-700': row.status === 'processing' || 'active',
                  'bg-yellow-100 text-yellow-800': row.status === 'pending',
                  'bg-red-100 text-red-800': row.status === 'closed',
                }"
              >
                {{ t(row.status) }}
              </span>
            </div>
          </template>
          <template #column-premium="{ row }">
            <div class="flex gap-2">
              <span
                v-if="row.premium"
                class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-1 text-xs font-semibold text-violet-600"
              >
                {{ t('yes') }}
              </span>
            </div>
          </template>
        </Grid>
      </div>
    </div>
  </AdminLayout>
</template>
