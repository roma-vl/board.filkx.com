<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-gray-50 pt-4 sm:justify-center sm:pt-0">
    <div class="flex flex-col items-center">
      <div
        class="mt-28 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-5xl sm:rounded-lg"
      >
        <Link
          href="/"
          class="flex flex-col items-center"
        >
          <ApplicationLogo class="w-28 fill-current text-gray-500 mb-4" />
        </Link>
        <slot />
      </div>
    </div>
  </div>
</template>
