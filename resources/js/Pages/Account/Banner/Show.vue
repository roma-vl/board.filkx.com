<script setup>
import { computed, ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Reject from '@/Pages/Admin/Banner/Actions/Reject.vue';
import Modal from '@/Components/Modal.vue';
import { getDateFormatFromLocale, getFullPathForImage } from '@/helpers.js';
import PayBanner from '@/Pages/Account/Banner/Actions/PayBanner.vue';

const props = defineProps({
  banner: {
    type: Object,
    default: () => ({}),
  },
});

const isRejectModalOpen = ref(false);
const isPayModalOpen = ref(false);
const bannerId = ref(null);

const form = useForm({});
const isDraft = computed(() => props.banner.status === 'draft');
const isOnModeration = computed(() => props.banner.status === 'moderation');
const isModerated = computed(() => props.banner.status === 'moderated');
const isActive = computed(() => props.banner.status === 'active');

const submitAction = (routeName) => {
  form.post(route(routeName, props.banner.id));
};

const publish = async () => {
  router.post(route('account.banners.actions.publish', { banner: props.banner.id }), {
    onSuccess: () => router.replace(route('admin.banners.index')),
  });
};

const toDraft = async () => {
  router.post(route('account.banners.actions.draft', { banner: props.banner.id }), {
    onSuccess: () => router.replace(route('admin.banners.index')),
  });
};
const activate = async () => {
  router.post(route('admin.banners.moderate', { banner: props.banner.id }), {
    onSuccess: () => router.replace(route('admin.banners.index')),
  });
};

const rejectAdvert = () => {
  bannerId.value = props.banner.id;
  isRejectModalOpen.value = true;
};

const payBanner = () => {
  isPayModalOpen.value = true;
};
const deleteAdvert = () => {
  if (confirm('Ви впевнені, що хочете видалити оголошення?')) {
    router.delete(route('account.banners.destroy', props.banner.id));
  }
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
        <div
          class="flex justify-between gap-2 mb-6 bg-white p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <div class="flex flex-row gap-2">
            <a
              :href="route('account.banners.edit', props.banner.id)"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded"
            >{{ $t('edit') }}</a>
            <button
              v-if="isDraft"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="publish"
            >
              {{ $t('publish') }}
            </button>
            <button
              v-if="isActive"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="submitAction('adverts.adverts.close')"
            >
              {{ $t('close') }}
            </button>
            <button
              v-if="isOnModeration || isActive"
              class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded"
              @click="toDraft"
            >
              {{ $t('to_draft') }}
            </button>
            <button
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
              @click="deleteAdvert"
            >
              {{ $t('delete') }}
            </button>
            <button
              v-if="isModerated"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="payBanner()"
            >
              {{ $t('pay') }}
            </button>
          </div>

          <div class="flex flex-row gap-2 items-center">
            <button
              v-if="isOnModeration"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="activate"
            >
              {{ $t('publish') }}
            </button>
            <button
              v-if="isOnModeration || isActive"
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
              @click="rejectAdvert"
            >
              {{ $t('reject') }}
            </button>
          </div>
        </div>
      </div>
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50">
        <div
          v-if="isDraft"
          class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4"
        >
          {{ $t('is_draft') }}
        </div>
        <div
          v-if="isOnModeration"
          class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4"
        >
          {{ $t('is_moderation') }}
        </div>
        <div
          v-if="banner.reject_reason"
          class="bg-red-100 text-red-800 p-3 rounded mb-4"
        >
          {{ $t('rejection_reason') }} : {{ banner.reject_reason }}
        </div>
        <div class="flex gap-6">
          <div class="w-2/3">
            <div class="bg-white rounded-lg shadow p-3 dark:bg-gray-700">
              <div class="w-full h-[600px] flex justify-center items-center">
                <img
                  :src="getFullPathForImage(banner.file)"
                  class="w-full h-full object-contain"
                  alt=""
                >
              </div>
            </div>
          </div>

          <div class="w-1/3">
            <div class="rounded-lg shadow p-3 bg-white dark:bg-gray-700">
              <p class="mt-4 text-gray-800 text-sm dark:text-gray-200">
                {{ $t('published') }} {{ getDateFormatFromLocale(banner.created_at) }}
              </p>
              <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-200">
                {{ banner.name }}
              </h1>
              <div class="mt-4 flex flex-row items-center">
                {{ $t('views_all') }} :
                <h2 class="text-xl font-bold text-green-600 pl-3">
                  {{ banner.limit }}
                </h2>
              </div>

              <div class="mt-4 flex flex-row items-center">
                {{ $t('views_left') }} :
                <h2 class="text-xl font-bold text-green-600 pl-3">
                  {{ banner.limit - banner.views }}
                </h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Modal
        :show="isRejectModalOpen"
        max-width="2xl"
        @close="isRejectModalOpen = false"
      >
        <Reject
          :banner-id="bannerId"
          @reject-created="isRejectModalOpen = false"
        />
      </Modal>
      <Modal
        :show="isPayModalOpen"
        max-width="2xl"
        @close="isPayModalOpen = false"
      >
        <PayBanner />
      </Modal>
    </div>
  </AuthenticatedLayout>
</template>
