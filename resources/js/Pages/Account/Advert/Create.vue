<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import AdvertFileUpload from '@/Pages/Account/Advert/Partials/AdvertFileUpload.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const props = defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  regions: {
    type: Array,
    default: () => [],
  },
});
const showLocationDropdown = ref(false);
const loadingCities = ref(false);
const citySearchQuery = ref('');
const filteredCities = ref([]);
const attributes = ref([]);
const form = useForm({
  category_id: '',
  region_id: '',
  title: '',
  price: '',
  address: '',
  content: '',
  attributes: {},
  images: [],
});

const submit = () => {
  form.post(route('account.adverts.store'), {
    onSuccess: () => {
      console.log('Оголошення створено');
      form.reset();
    },
  });
};

const getCategoryOptions = (categories, prefix = '') => {
  let options = [];
  categories.forEach((category) => {
    options.push({ id: category.id, name: prefix + category.name });

    if (category.children_recursive && category.children_recursive.length) {
      options = options.concat(getCategoryOptions(category.children_recursive, prefix + '- '));
    }
  });
  return options;
};
const formattedCategories = computed(() => getCategoryOptions(props.categories));
const selectCity = (city) => {
  citySearchQuery.value = city.name;
  form.region_id = city.id;
  showLocationDropdown.value = false;
};
const searchCities = async () => {
  if (citySearchQuery.value.length < 2) {
    filteredCities.value = [];
    return;
  }

  loadingCities.value = true;
  try {
    const response = await axios.get(
      route('adverts.regions.search', { region: citySearchQuery.value })
    );
    filteredCities.value = response.data.regions;
    if (response.data.regions.length) {
      showLocationDropdown.value = true;
    }
  } finally {
    loadingCities.value = false;
  }
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.search-container')) {
    showLocationDropdown.value = false;
  }
};
watch(citySearchQuery, searchCities);

watch(
  () => form.region_id,
  async (newRegionId) => {
    form.area_id = '';
    form.village_id = '';
    areas.value = [];

    if (!newRegionId) return;

    try {
      const response = await axios.get(route('account.adverts.areas', { regionId: newRegionId }));
      areas.value = response.data;
    } catch (error) {
      console.error('Помилка завантаження районів', error);
    }
  }
);

watch(
  () => form.category_id,
  async (newCategoryId) => {
    attributes.value = [];
    if (!newCategoryId) return;

    try {
      const response = await axios.get(
        route('account.adverts.attributes', { categoryId: newCategoryId })
      );
      attributes.value = response.data ?? [];
    } catch (error) {
      console.error('Помилка завантаження атрибутів', error);
    }
  }
);

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <Head :title="t('CreateAdvert')" />
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-6 dark:bg-gray-700 rounded-md shadow-md"
        >
          <div class="px-4">
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Title') }}</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                >
                <InputError
                  class="mt-2"
                  :message="form.errors.title"
                />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Price') }}</label>
                <input
                  v-model="form.price"
                  type="number"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                >
                <InputError
                  class="mt-2"
                  :message="form.errors.price"
                />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Category') }}</label>
                <select
                  v-model="form.category_id"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                >
                  <option
                    v-for="category in formattedCategories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
                <InputError
                  class="mt-2"
                  :message="form.errors.category_id"
                />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Photos') }}</label>
                <AdvertFileUpload v-model="form.images" />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Location') }}</label>
                <div class="relative search-container">
                  <input
                    v-model="citySearchQuery"
                    type="text"
                    class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200 dark:bg-gray-800 dark:border-gray-900"
                    :placeholder="t('StartTypingAddress')"
                  >
                  <div
                    v-if="showLocationDropdown"
                    class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto dark:bg-gray-800 dark:border-gray-900"
                  >
                    <ul v-if="filteredCities.length">
                      <li
                        v-for="city in filteredCities"
                        :key="city.id"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200 dark:bg-gray-800 dark:border-gray-900"
                        @click="selectCity(city)"
                      >
                        {{ city.name }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <InputError
                class="mt-2"
                :message="form.errors.region_id"
              />

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Address') }}</label>
                <input
                  v-model="form.address"
                  type="text"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                >
              </div>

              <InputError
                class="mt-2"
                :message="form.errors.address"
              />

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ t('Description') }}</label>
                <textarea
                  v-model="form.content"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                />
                <InputError
                  class="mt-2"
                  :message="form.errors.content"
                />
              </div>

              <div v-if="attributes && attributes.length > 0">
                <h3 class="text-lg font-medium">
                  {{ t('Attributes') }}
                </h3>
                <div
                  v-for="attribute in attributes"
                  :key="attribute"
                  class="mb-4"
                >
                  <label
                    :for="'attributes.' + attribute.id"
                    class="block text-sm font-medium mb-2"
                  >
                    {{ attribute.name }}
                  </label>
                  <template v-if="attribute.variants?.length">
                    <select
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                    >
                      <option value="" />
                      <option
                        v-for="variant in attribute.variants"
                        :key="variant"
                        :value="variant"
                      >
                        {{ variant }}
                      </option>
                    </select>
                  </template>
                  <template v-else-if="['integer', 'float'].includes(attribute.type)">
                    <input
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      type="number"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                    >
                  </template>
                  <template v-else>
                    <input
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      type="text"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
                    >
                  </template>
                </div>
              </div>

              <button
                type="submit"
                class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                {{ t('Create') }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
