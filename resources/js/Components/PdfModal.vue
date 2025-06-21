<script setup>
import { useI18n } from 'vue-i18n';

const props = defineProps({
  visible: Boolean,
  pdfUrl: String,
});
const emit = defineEmits(['close']);
const { t } = useI18n();

const close = () => emit('close');
</script>

<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center"
  >
    <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] p-4 relative">
      <button
        class="absolute top-2 right-4 text-gray-700 hover:text-red-600 text-3xl"
        @click="close"
      >
        ×
      </button>

      <h2 class="text-xl font-semibold mb-2">
        Чек замовлення
      </h2>

      <iframe
        :src="pdfUrl"
        class="w-full h-[70vh]"
        frameborder="0"
      />

      <div class="mt-4 flex justify-end">
        <a
          :href="pdfUrl"
          download
          class="btn btn-primary px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          {{ t('Download') }}
        </a>
      </div>
    </div>
  </div>
</template>
