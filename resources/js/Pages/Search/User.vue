<script setup>
import { Head, router } from '@inertiajs/vue3';
import CategoryAdvert from '@/Components/Advert/Category/CategoryAdvert.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref, watch } from 'vue';
import debounce from 'lodash.debounce';
import CategoryItem from '@/Components/Advert/Show/CategoryItem.vue';
import { getDateFormatFromLocale, getFullPathForAvatarImage } from '@/helpers.js';

const props = defineProps({
  adverts: Object,
  user: Object,
  query: Object,
  categoriesCounts: Object,
});

const query = ref(props.query?.query || '');

const filteredCategories = ref([]);

const onSearch = debounce(() => {
  if (query.value.length < 3) return;
  router.get(
    route('list.advert.user', props.user.id),
    { query: query.value },
    {
      preserveState: true,
      replace: true,
      only: ['adverts'],
      onSuccess: () => {
        extractCategories();
      },
    }
  );
}, 300);

function extractCategories() {
  const categoriesMap = {};
  props.adverts.data.forEach((advert) => {
    if (advert.category && !categoriesMap[advert.category.id]) {
      categoriesMap[advert.category.id] = advert.category;
    }
  });
  filteredCategories.value = Object.values(categoriesMap);
}

watch(
  () => props.adverts,
  () => extractCategories(),
  { immediate: true }
);

const categoriesCounts = ref(props.categoriesCounts || []);
const mainCategory = categoriesCounts.value.filter((cat) => cat.parent_id === null)[0];

const categoryTree = computed(() => {
  const map = {};
  const roots = [];

  const categories = categoriesCounts.value.filter((cat) => cat.parent_id !== null);

  categories.forEach((cat) => {
    cat.children = [];
    cat.expanded = false;
    map[cat.id] = cat;
  });

  categories.forEach((cat) => {
    if (map[cat.parent_id]) {
      map[cat.parent_id].children.push(cat);
    } else {
      roots.push(cat);
    }
  });

  return roots;
});

function toggleExpand(category) {
  category.expanded = !category.expanded;
}

function onCategorySelect(categoryId) {
  router.get(
    route('list.advert.user', props.user.id),
    {
      query: query.value,
      category_id: categoryId,
    },
    {
      preserveState: true,
      replace: true,
      only: ['adverts'],
    }
  );
}

function shareProfile() {
  const url = window.location.href;
  navigator.clipboard.writeText(url);
  alert('Посилання скопійовано!');
}

function subscribeToUser() {
  alert(`Ви підписались на ${props.user.name}`);
}
</script>

<template>
  <Head :title="$t('ads.categories_title')" />
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="bg-white shadow-xl rounded-3xl overflow-hidden dark:bg-gray-700 dark:text-gray-100"
        >
          <div class="rounded-2xl p-6 shadow bg-gray-200 dark:bg-gray-900 m-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
              <!-- LEFT COLUMN: User info -->
              <div class="flex items-start gap-4">
                <!-- Avatar -->
                <img
                  class="w-16 h-16 rounded-full object-cover"
                  :src="getFullPathForAvatarImage(user.avatar_url)"
                  alt="avatar"
                >
                <div class="p-3">
                  <h2 class="text-xl font-semibold">
                    {{ user.name }}
                  </h2>
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    Ще не має рейтингу
                  </p>
                </div>

                <!-- User details -->
                <div class="space-y-1 pt-4 pl-6">
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    На сайті з {{ getDateFormatFromLocale(user.created_at) }}
                  </p>
                  <div
                    class="flex items-center gap-2 mt-2 text-green-600 dark:text-green-400 text-sm"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 10l1.293 1.293a1 1 0 001.414 0L12 5.414l6.293 6.293a1 1 0 001.414 0L21 10m-9 8v-6"
                      />
                    </svg>
                    10+ успішних доставок
                  </div>
                </div>
              </div>

              <!-- RIGHT COLUMN: Buttons -->
              <div class="flex flex-col md:flex-row md:justify-end gap-3">
                <button
                  class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                  @click="shareProfile"
                >
                  Поширити
                </button>
                <button
                  class="px-4 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 transition"
                  @click="subscribeToUser"
                >
                  Підписатися
                </button>
              </div>
            </div>
          </div>

          <div class="flex">
            <!-- Left Panel: Search and Categories -->
            <div
              class="w-full md:w-1/3 border-r border-gray-300 dark:border-gray-600 p-6 space-y-4"
            >
              <input
                v-model="query"
                type="text"
                placeholder="Пошук оголошень..."
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-800 dark:text-white"
                @input="onSearch"
              >
              <h2 class="text-lg font-semibold">
                Категорії
              </h2>

              <ul class="space-y-1">
                <li class="flex flex-col">
                  <span
                    class="cursor-pointer text-blue-600 hover:underline dark:text-blue-400 p-1"
                    @click="onCategorySelect(mainCategory.id)"
                  >
                    {{ mainCategory.name }}
                    <span
                      class="text text-gray-500 dark:text-gray-800 flex-row bg-purple-500/50 dark:bg-purple-500/50 rounded-lg p-1 ml-3"
                    >
                      ({{ mainCategory.count }})</span>
                  </span>
                </li>

                <CategoryItem
                  v-for="category in categoryTree"
                  :key="category.id"
                  :category="category"
                  @toggle="toggleExpand"
                  @select="onCategorySelect"
                />
              </ul>
            </div>

            <div class="w-full md:w-2/3 p-6">
              <div>
                <h1 class="text-2xl font-semibold mb-4">
                  Оголошення користувача {{ user.name }}
                </h1>
              </div>

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
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
