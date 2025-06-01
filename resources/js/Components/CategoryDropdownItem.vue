<script setup>
import { ref } from 'vue';
import ArrowRightIcon from '@/Components/Icon/ArrowRightIcon.vue';
import ArrowLeftIcon from '@/Components/Icon/ArrowLeftIcon.vue';

const props = defineProps({
  category: {
    type: Object,
    default: () => ({}),
  },
});
const emit = defineEmits(['select']);

const isOpen = ref(false);

function toggleChildren() {
  isOpen.value = !isOpen.value;
}

function select(category) {
  emit('select', category);
}
</script>

<template>
  <div>
    <div
      class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-800"
      @click="toggleChildren"
    >
      <span
        class="w-full"
        @click.stop="select(category)"
      >{{ category.name }}</span>
      <span
        v-if="category.children?.length"
        class=""
      >
        <ArrowLeftIcon
          v-if="isOpen"
          class="w-6 h-6 ml-2 rounded dark:hover:bg-gray-700"
        />
        <ArrowRightIcon
          v-else
          class="w-6 h-6 ml-2 rounded dark:hover:bg-gray-700"
        />
      </span>
    </div>

    <div
      v-if="isOpen && category.children?.length"
      class="ml-4 border-l"
    >
      <CategoryDropdownItem
        v-for="child in category.children"
        :key="child.id"
        :category="child"
        @select="select"
      />
    </div>
  </div>
</template>

<style scoped>
.rotate-90 {
  transform: rotate(90deg);
}
</style>
