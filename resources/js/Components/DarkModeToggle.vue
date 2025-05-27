<script setup>
import { ref, watchEffect } from 'vue';

const isDark = ref(localStorage.theme === 'dark');

watchEffect(() => {
  document.documentElement.classList.toggle('dark', isDark.value);
  localStorage.theme = isDark.value ? 'dark' : 'light';
});

function toggleDark() {
  isDark.value = !isDark.value;
}
</script>

<template>
  <button
    class="p-2 rounded dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition"
    title="Toggle Dark Mode"
    @click="toggleDark"
  >
    <span v-if="isDark">🌙</span>
    <span v-else>☀️</span>
  </button>
</template>
