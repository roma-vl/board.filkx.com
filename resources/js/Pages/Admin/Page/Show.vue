<script setup>
import { computed, defineProps } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  page: {
    type: Object,
    default: () => ({}),
  },
});
function decodeHtml(html) {
  const txt = document.createElement('textarea');
  txt.innerHTML = html;
  return txt.value;
}

const decodedContent = computed(() => decodeHtml(props.page.content));
</script>

<template>
  <Head title="Категорія - Атрибути" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-2 flex justify-end">
          <Link
            :href="route('admin.pages.index')"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
          >
            {{ $t('pages') }}
          </Link>
        </div>

        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
          <h1 class="text-2xl font-bold">
            {{ $t('title') }}: {{ props.page.title }}
          </h1>
          <p class="text-gray-600">
            {{ $t('slug') }} : {{ props.page.slug }}
          </p>

          {{ $t('content') }}:
          <!-- eslint-disable-next-line vue/no-v-html -->
          <div v-html="decodedContent" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
