<script setup>
import { watch, computed } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps({
  flash: {
    type: Object,
    required: true,
  },
});

const messages = computed(() => {
  return Object.entries(props.flash)
    .map(([type, message]) => ({ type, message }))
    .filter(({ message }) => message);
});

showToasts(messages.value);

// слухати зміну flash
watch(
  () => props.flash,
  (newVal) => {
    const newMessages = Object.entries(newVal)
      .map(([type, message]) => ({ type, message }))
      .filter(({ message }) => message);

    showToasts(newMessages);
  }
);

function showToasts(msgs) {
  for (const { type, message } of msgs) {
    switch (type) {
      case 'success':
        toast.success(message);
        break;
      case 'error':
        toast.error(message);
        break;
      case 'info':
        toast.info(message);
        break;
      case 'warning':
        toast.warning(message);
        break;
      default:
        toast(message);
    }
  }
}
</script>

<template>
  <div style="display: none" />
</template>
