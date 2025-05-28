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

const emit = defineEmits(['attributeUpdated']);

const form = useForm({
  name: props.data.attribute.name,
  type: props.data.attribute.type,
  is_required: Boolean(props.data.attribute.is_required),
  variants: [...props.data.attribute.variants], // масив
  sort: props.data.attribute.sort,
});

const submit = () => {
  form.post(
    route('admin.adverts.category.attributes.update', {
      category: props.data.category.id,
      attribute: props.data.attribute.id,
    }),
    {
      onSuccess: () => {
        emit('attributeUpdated');
        form.reset();
      },
    }
  );
};
</script>

<template>
  <Head :title="$t('edit.attribute')" />
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
      {{ $t('edit.attribute') }}
    </h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('title') }}</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full p-2 border rounded"
          required
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('type') }}</label>
        <select
          v-model="form.type"
          class="w-full p-2 border rounded"
        >
          <option :value="null">
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
            class="rounded"
          >
          <span>{{ $t('required') }}</span>
        </label>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('variants') }} ( {{ $t('one.per.line') }} )</label>
        <TagInput v-model="form.variants" />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">{{ $t('sort') }}</label>
        <input
          v-model="form.sort"
          type="number"
          class="w-full p-2 border rounded"
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
