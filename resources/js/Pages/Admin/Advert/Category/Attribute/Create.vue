<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';
import TagInput from '@/Components/TagInput.vue';

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});
const emit = defineEmits(['attributeCreated']);

const form = useForm({
  name: '',
  type: null,
  is_required: false,
  variants: '',
  sort: 1,
});

const submit = () => {
  form.post(
    route('admin.adverts.category.attributes.store', {
      category: props.data.category.id,
    }),
    {
      onSuccess: () => {
        emit('attributeCreated');
        form.reset();
      },
    }
  );
};
</script>

<template>
  <Head :title="$t('add.attribute')" />
  <div class="p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
    <h1 class="text-2xl font-bold mb-4">
      {{ $t('add.attribute') }}
    </h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-200">{{ $t('title') }}</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full p-2 border rounded dark:bg-gray-900"
          required
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-200">{{ $t('type') }}</label>
        <select
          v-model="form.type"
          class="w-full p-2 border rounded dark:bg-gray-900"
        >
          <option
            :value="null"
            class="dark:text-gray-200"
          >
            {{ $t('select.type') }}
          </option>
          <option
            v-for="(label, key) in props.data.types"
            :key="key"
            :value="label"
          >
            {{ label }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input
            v-model="form.is_required"
            type="checkbox"
            class="rounded dark:bg-gray-900"
          >
          <span>{{ $t('required') }}</span>
        </label>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-100">{{ $t('variants') }} ( {{ $t('one.per.line') }} )</label>
        <TagInput v-model="form.variants" />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('sort') }}</label>
        <input
          v-model="form.sort"
          type="number"
          class="w-full p-2 border rounded dark:bg-gray-900"
          required
        >
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
