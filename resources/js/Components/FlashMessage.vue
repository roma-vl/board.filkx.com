<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'info', // 'success', 'error', 'info', etc.
    },
    message: {
        type: String,
        required: true,
    },
});

const classes = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-200 text-green-800 border-green-400';
        case 'error':
            return 'bg-red-200 text-red-800 border-red-400';
        case 'info':
        default:
            return 'bg-blue-200 text-blue-800 border-blue-400';
    }
});
</script>

<template>
    <div v-if="message" :class="`mb-4 p-2 rounded border ${classes}`">
        {{ message }}
    </div>
</template>
