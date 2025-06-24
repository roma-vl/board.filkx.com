<script setup>
import InfoIcon from "@/Components/Icon/InfoIcon.vue";

defineProps({
    message: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="relative inline-block text-gray-500">
        <div class="peer inline-block cursor-pointer">
            <InfoIcon />
        </div>
        <div
            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 w-64 bg-gray-800 text-white text-sm rounded p-3 opacity-0 peer-hover:opacity-100 transition-opacity duration-200 z-50 shadow-lg pointer-events-none"
        >
            {{ message }}
        </div>
    </div>
</template>
