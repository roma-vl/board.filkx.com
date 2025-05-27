<script setup lang="ts">
import { getFullPathForImage } from '@/helpers.js';
</script>

<template>
  <img
    :src="getFullPathForImage('/images/logo/1.png')"
    alt="Logo"
  >
</template>
