<script setup>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const emit = defineEmits(['close']);
const props = defineProps({
  image: String,
  default: null,
});

const cropperRef = ref();

function save() {
  const canvas = cropperRef.value.getResult()?.canvas;
  if (!canvas) return;

  canvas.toBlob((blob) => {
    const formData = new FormData();
    formData.append('avatar', blob, 'avatar.jpg'); // тут назва має бути avatar

    router.post(route('account.profile.avatar.upload'), formData, {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: () => {
        emit('close');
      },
    });
  }, 'image/jpeg');
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-[500px] max-w-full dark:bg-gray-800">
      <Cropper
        ref="cropperRef"
        :src="image"
        :stencil-props="{ aspectRatio: 1 }"
        :auto-zoom="true"
        :resize-image="true"
      />
      <div class="flex justify-end gap-2 mt-4">
        <button
          class="px-4 py-2 bg-gray-500 text-white rounded"
          @click="emit('close')"
        >
          Cancel
        </button>
        <button
          class="px-4 py-2 bg-blue-600 text-white rounded"
          @click="save"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>
