<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex flex-col items-center min-h-screen">
      <div
        class="mt-28 w-full overflow-hidden bg-white dark:bg-gray-800 px-6 py-6 shadow-md sm:max-w-5xl sm:rounded-lg transition-all duration-300"
      >
        <Link
          href="/"
          class="flex flex-col items-center"
        >
          <ApplicationLogo
            class="w-28 fill-current text-indigo-600 dark:text-indigo-400 mb-4 transition-colors"
          />
        </Link>
        <slot />
      </div>
    </div>
  </div>
</template>
