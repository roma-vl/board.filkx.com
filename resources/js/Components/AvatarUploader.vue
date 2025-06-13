<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CropImageModal from '@/Components/CropImageModal.vue';
import { getFullPathForAvatarImage } from '@/helpers.js';

const user = computed(() => usePage().props.auth.user);
const showModal = ref(false);
const imageSrc = ref(null);

function handleFileChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    imageSrc.value = reader.result;
    showModal.value = true;
  };
  reader.readAsDataURL(file);
}
</script>

<template>
  <div
    class="relative group w-60 h-60 rounded-full overflow-hidden shadow-md cursor-pointer"
    @click="() => $refs.fileInput.click()"
  >
    <img
      :src="getFullPathForAvatarImage(user.avatar_url)"
      class="w-full h-full object-cover transition-all duration-300"
    >
    <div
      class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
    >
      <svg
        class="w-10 h-10 text-white"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V7h-2.828z"
        />
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M16 7V3H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-8h-4z"
        />
      </svg>
    </div>
    <input
      ref="fileInput"
      type="file"
      class="hidden"
      accept="image/*"
      @change="handleFileChange"
    >
  </div>

  <CropImageModal
    v-if="showModal"
    :image="imageSrc"
    @close="showModal = false"
  />
</template>
