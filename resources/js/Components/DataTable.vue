<script setup>
import ArrowUpDownIcon from '@/Components/Icon/ArrowUpDownIcon.vue';
import { computed } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  headings: {
    type: Array,
    required: true,
  },
  uniqueKey: {
    type: String,
    default: 'id',
  },
  searchQuery: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['sort']);
const sortField = JSON.parse(localStorage.getItem('sortField'));
const highlightText = (text, query) => {
  if (!query || typeof text !== 'string') return text;
  const regex = new RegExp(`(${query})`, 'gi');
  return text.replace(regex, '<span class="bg-yellow-200">$1</span>');
};

const processedItems = computed(() => {
  return props.items.map((item) => {
    const newItem = { ...item };
    props.headings.forEach((heading) => {
      if (heading.highlight && typeof heading.highlight === 'string') {
        newItem[heading.key] = highlightText(item[heading.key], props.searchQuery);
      }
    });
    return newItem;
  });
});
</script>

<template>
  <table
    class="w-full border-collapse bg-white text-left text-sm text-gray-500 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700"
  >
    <thead class="bg-gray-50">
      <tr>
        <th
          v-for="heading in props.headings"
          :key="heading.key"
          class="border-b px-3 py-3 text-gray-700 uppercase text-xs flex-col dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-900"
          :class="{
            'border-b bg-gray-100 border-gray-900 text-gray-900 font-bold ':
              sortField === heading.key && heading.sortable === true,
            'cursor-pointer hover:bg-gray-100': heading.sortable === true,
          }"
          @click="emit('sort', heading.key)"
        >
          <div class="flex gap-2">
            <span class="inline-flex items-center gap-1 dark:text-gray-200">
              {{ heading.value }}
            </span>
            <span
              class="flex items-end gap-1 cursor-pointer text-gray-400 hover:text-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
              :class="{ 'text-gray-900 font-bold': sortField === heading.key }"
            >
              <ArrowUpDownIcon
                v-if="heading.sortable"
                class="w-4 h-4"
              />
            </span>
          </div>
        </th>
      </tr>
    </thead>
    <tbody
      class="divide-y divide-gray-200 border-t dark:divide-gray-900 border-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700"
    >
      <tr
        v-for="item in processedItems"
        :key="item[uniqueKey]"
        class="hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700"
      >
        <td
          v-for="heading in headings"
          :key="heading.key"
          class="px-6 py-4 text-gray-600 dark:text-gray-200"
        >
          <slot
            :name="`column-${heading.key}`"
            :row="item"
          >
            <span v-if="heading.highlight" />
            <span v-else>{{ item[heading.key] }}</span>
          </slot>
        </td>
      </tr>
    </tbody>
  </table>
</template>
