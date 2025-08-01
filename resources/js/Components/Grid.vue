<script setup>
import { computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import DataTableHeader from '@/Components/DataTableHeader.vue';
import DataTable from '@/Components/DataTable.vue';
import Pagination from '@/Components/Pagination.vue';
import { useLocalStorageFn } from '@/helpers.js';
import debounce from 'lodash.debounce';

const props = defineProps({
  items: { type: Array, required: true },
  pagination: { type: Object, required: true },
  headings: { type: Array, required: true },
  routes: { type: Array, required: true },
});

const MIN_SEARCH_LENGTH = 1;
const PER_PAGE_DEFAULT = 10;

const searchQuery = useLocalStorageFn('searchQuery', '');
const perPage = useLocalStorageFn('perPage', PER_PAGE_DEFAULT);
const sortField = useLocalStorageFn('sortField', 'id');
const sortOrder = useLocalStorageFn('sortOrder', 'asc');
const visibleColumns = useLocalStorageFn(
  'visibleColumns',
  props.headings.map((h) => h.key)
);

const emit = defineEmits(['update:searchQuery']);

watch(searchQuery, (newValue) => {
  emit('update:searchQuery', newValue);
});

const queryParams = computed(() => ({
  search: searchQuery.value,
  per_page: perPage.value,
  sort_by: sortField.value,
  sort_order: sortOrder.value,
}));
const queryParams2 = computed(() => ({
  per_page: perPage.value,
  sort_by: sortField.value,
  sort_order: sortOrder.value,
}));

const updateItemsList = debounce(() => {
  const routesMap = props.routes.reduce((acc, route) => {
    acc[route.key] = route.value;
    return acc;
  }, {});

  console.log('queryParams.value.search', queryParams.value.search);
  const url = queryParams.value.search ? routesMap.search : routesMap.index;
  if (queryParams.value.search === '') {
    localStorage.setItem('searchQuery', JSON.stringify(''));
    router.get(route(url), queryParams2.value, { preserveScroll: true, replace: true });
  } else {
    router.get(route(url), queryParams.value, { preserveScroll: true, replace: true });
  }
}, 300);

watch(queryParams, updateItemsList, { deep: true });

const updateSorting = (field) => {
  const heading = props.headings.find((h) => h.key === field);
  if (!heading || !heading.sortable) return;

  if (sortField.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortOrder.value = 'asc';
  }
  updateItemsList();
};
</script>

<template>
  <div class="grid-container shadow p-2 bg-white rounded-lg dark:bg-gray-700">
    <DataTableHeader
      v-model:search-query="searchQuery"
      v-model:per-page="perPage"
      v-model:visible-columns="visibleColumns"
      :headings="headings"
      :per-page-values="[5, 10, 20, 50]"
    />
    <DataTable
      v-if="items"
      :items="items"
      :headings="headings.filter((h) => visibleColumns.includes(h.key))"
      unique-key="id"
      :search-query="searchQuery"
      @sort="updateSorting"
    >
      <template
        v-for="(_, slotName) in $slots"
        #[slotName]="scope"
      >
        <slot
          :name="slotName"
          v-bind="scope"
        />
      </template>
    </DataTable>
    <div
      v-else
      class="w-full border-collapse bg-white text-center text text-gray-800 border-b mb-2"
    >
      {{ $t('No data found') }}.
    </div>

    <Pagination
      :pagination="pagination"
      :search-query="searchQuery"
      :sort-field="sortField"
      :sort-order="sortOrder"
    />
  </div>
</template>
