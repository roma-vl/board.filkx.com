<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CategoryAdvert from '@/Components/Advert/Category/CategoryAdvert.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import ChildCategories from '@/Components/Advert/Category/ChildCategories.vue';
import CategoryDropdown from '@/Components/CategoryDropdown.vue';
import Get from '@/Pages/Banner/Get.vue';
import { useSearch } from '@/composables/useSearch.js';
import SearchInput from '@/Components/Search/SearchInput.vue';
import LocationSelector from '@/Components/Search/LocationSelector.vue';

const adverts = usePage().props.adverts;
const queryFilter = ref(usePage().props.query || {});
const props = defineProps({
  categories: {
    type: Object,
    default: () => ({}),
  },
  locations: {
    type: Object,
    default: () => ({}),
  },
  childCategories: {
    type: Object,
    default: () => ({}),
  },
  regionsCounts: {
    type: Object,
    default: () => ({}),
  },
  categoriesCounts: {
    type: Object,
    default: () => ({}),
  },
  attributes: {
    type: Object,
    default: () => ({}),
  },
  categoryFilters: {
    type: Object,
    default: () => ({}),
  },
  activeCategory: {
    type: Object,
    default: () => ({}),
  },
  activeRegion: {
    type: Object,
    default: () => ({}),
  },
});

const { searchQuery, cityIdSearchQuery, search } = useSearch();

const toggle = ref('');
const handleCitySelect = (slug) => {
  cityIdSearchQuery.value = slug;
};

const handleSearch = (text) => {
  searchQuery.value = text;
};

const handleModel = (newToggle) => {
  toggle.value = newToggle;
};

const selectedCategoryId = computed(() => queryFilter.value.category_id);

const lastLocation = computed(() => {
  const entries = Object.entries(props.locations);
  if (!entries.length) return null;
  const [_, lastValue] = entries[entries.length - 1];
  return lastValue;
});

const selectedCategoryAttributes = computed(() => {
  const selected = findCategoryById(props.categoryFilters, Number(selectedCategoryId.value));
  return selected?.attributes ?? [];
});

function findCategoryById(categories, id) {
  for (const category of categories) {
    if (category.id === id) return category;
    if (category.children?.length) {
      const found = findCategoryById(category.children, id);
      if (found) return found;
    }
  }
  return null;
}
const handleCategoryChange = (category) => {
  const filteredQuery = { ...queryFilter.value };

  Object.keys(filteredQuery).forEach((key) => {
    const val = filteredQuery[key];
    if (val === null || val === '' || typeof val === 'undefined') {
      delete filteredQuery[key];
    }
  });

  const fullPath = getCategorySlugsPathById(props.categoryFilters, category.id);
  const categories = getCategorySlugs(props.categoryFilters);
  const path = window.location.pathname;

  const cleanPath = path
    .split('/')
    .filter((segment) => segment && !categories.includes(segment))
    .filter((segment) => segment && segment !== 'list')
    .join('/');

  const newPath = `/${fullPath.join('/')}`;

  if (window.location.pathname === newPath + '/' + cleanPath) return;

  router.visit(newPath + '/' + cleanPath + window.location.search);
};

watch(
  () => queryFilter.value.category_id,
  (newVal) => {
    if (!newVal) return;
    const selected = findCategoryById(props.categoryFilters, Number(newVal));
    if (selected && toggle && toggle?.value === 'open') {
      handleCategoryChange(selected);
    }
  }
);

watch(
  () => usePage().props.query,
  (newQuery) => {
    queryFilter.value = newQuery || {};
    if (queryFilter.value.query) {
      searchQuery.value = queryFilter.value.query;
    } else {
      searchQuery.value = '';
    }
  },
  { immediate: true }
);

function getCategorySlugs(categories) {
  const slugs = [];

  for (const category of categories) {
    slugs.push(category.slug);

    if (category.children?.length) {
      slugs.push(...getCategorySlugs(category.children));
    }
  }

  return slugs;
}

function getCategorySlugsPathById(categories, id, path = []) {
  for (const category of categories) {
    const newPath = [...path, category.slug];
    if (category.id === id) {
      return newPath;
    }
    if (category.children?.length) {
      const result = getCategorySlugsPathById(category.children, id, newPath);
      if (result) {
        return result;
      }
    }
  }
  return null;
}

onMounted(() => {
  const pathParts = window.location.pathname.split('/').filter(Boolean);

  if (!pathParts.length) return;

  cityIdSearchQuery.value = props.activeRegion;

  if (queryFilter.value.category_id !== props.activeCategory.id) {
    queryFilter.value.category_id = props.activeCategory.id;
  }

  if (queryFilter.value.query) {
    searchQuery.value = queryFilter.value.query;
  }
});

const banner = ref(null);

onMounted(async () => {
  const res = await axios.get('/banner/get', {
    params: {
      category: selectedCategoryId?.value,
      region: lastLocation?.value?.id,
      format: '240x400',
    },
  });
  console.log(res, 'res.data.banner');
  banner.value = res.data.banner;
});
</script>

<template>
  <Head :title="$t('ads.categories_title')" />
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="bg-white shadow-xl rounded-3xl overflow-hidden dark:bg-gray-700 dark:text-gray-100"
        >
          <div class="p-8">
            <!-- Пошук -->

            <div
              class="flex flex-col md:flex-row items-center gap-6 bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 p-6 rounded-3xl shadow-2xl border border-purple-200/30 dark:bg-gradient-to-br dark:from-gray-900 dark:gray-800 dark:gray-700"
            >
              <SearchInput
                v-model="searchQuery"
                @select-suggestion="handleSearch"
              />
              <div class="flex flex-col md:flex-row items-center gap-4 w-full">
                <LocationSelector
                  v-model="cityIdSearchQuery"
                  class="w-full md:w-auto md:rounded-lg"
                  @select-city="handleCitySelect"
                />
                <button
                  class="w-full md:w-auto md:rounded-lg px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-semibold hover:scale-105 active:scale-95 transition-transform duration-150 shadow-lg"
                  @click="search(getCategorySlugs(props.categoryFilters))"
                >
                  {{ $t('search.button') }}
                </button>
              </div>
            </div>

            <!-- Фільтри -->
            <h2
              class="text-2xl font-bold text-gray-800 mt-8 mb-6 border-b border-gray-200 pb-2 dark:text-gray-300 dark:border-gray-500"
            >
              {{ $t('filters.title') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
              <!-- Категорії -->
              <div
                class="bg-white border border-gray-100 dark:border-gray-900 rounded-2xl p-5 shadow-md transition-all hover:shadow-lg dark:bg-gray-800"
              >
                <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-400">
                  {{ $t('filters.category') }}
                </label>
                <CategoryDropdown
                  v-model="queryFilter.category_id"
                  :category-filters="props.categoryFilters"
                  @toggle="handleModel"
                />
              </div>

              <!-- Атрибути -->
              <div
                v-for="(attribute, index) in selectedCategoryAttributes"
                :key="index"
                class="bg-white border border-gray-100 dark:border-gray-900 rounded-2xl p-5 shadow-md transition-all hover:shadow-lg dark:bg-gray-800"
              >
                <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-400">
                  {{ attribute.name }}
                </label>

                <select
                  v-if="attribute.variants && attribute.variants.length"
                  v-model="queryFilter[attribute.id]"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition dark:bg-gray-700"
                >
                  <option
                    value=""
                    class="dark:text-gray-300"
                  >
                    {{ $t('filters.select') }}
                  </option>
                  <option
                    v-for="(variant, i) in attribute.variants"
                    :key="i"
                    :value="variant"
                  >
                    {{ variant }}
                  </option>
                </select>

                <div
                  v-else-if="attribute.type === 'integer'"
                  class="flex gap-3"
                >
                  <input
                    v-model="queryFilter[`${attribute.id}_from`]"
                    type="number"
                    :placeholder="$t('filters.from')"
                    class="w-1/2 px-4 py-2 border border-gray-300 dark:border-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition dark:bg-gray-700"
                  >
                  <input
                    v-model="queryFilter[`${attribute.id}_to`]"
                    type="number"
                    :placeholder="$t('filters.to')"
                    class="w-1/2 px-4 py-2 border border-gray-300 dark:border-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition dark:bg-gray-700"
                  >
                </div>

                <input
                  v-else-if="attribute.type === 'string'"
                  v-model="queryFilter[attribute.id]"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition dark:bg-gray-700"
                >
              </div>
            </div>

            <!-- Хлібні крихти -->
            <Breadcrumbs
              class="mb-6"
              :categories="props.categories"
              :locations="props.locations"
            />

            <!-- Дочірні категорії -->
            <ChildCategories
              :child-categories="props.childCategories"
              :categories-counts="props.categoriesCounts"
              :categories="props.categories"
            />

            <div v-if="adverts.data.length">
              <CategoryAdvert :adverts="adverts" />
            </div>
            <div
              v-else
              class="m-32 text-lg"
            >
              <h2 class="text-xl mb-4 text-center">
                {{ $t('search.empty') }}
              </h2>
            </div>
            <Get :banner="banner" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
