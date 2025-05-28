<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineEmits } from 'vue';

const emit = defineEmits(['categoryCreated']);
const categories = usePage().props.categories;

const form = useForm({
  name: '',
  parent_id: null,
});

const submit = () => {
  form.post(route('admin.adverts.category.store'), {
    onSuccess: () => emit('categoryCreated'),
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
const formattedCategories = computed(() => getCategoryOptions(categories));
</script>

<template>
  <Head :title="$t('add.category')" />

  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
      {{ $t('add.category') }}
    </h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('title') }}</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full p-2 border rounded"
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('slug') }}</label>
        <input
          v-model="form.slug"
          type="text"
          class="w-full p-2 border rounded"
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('parent.category') }}</label>
        <select
          v-model="form.parent_id"
          class="w-full p-2 border rounded"
        >
          <option :value="null">
            - {{ $t('without.parent.category') }}
          </option>
          <option
            v-for="cat in formattedCategories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded"
      >
        {{ $t('Save') }}
      </button>
    </form>
  </div>
</template>
