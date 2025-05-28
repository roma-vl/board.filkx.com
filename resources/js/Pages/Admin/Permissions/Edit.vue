<script setup>
import { defineProps, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});
const emit = defineEmits(['permissionUpdated']);

const form = useForm({
  key: props.data.permission.key,
  description: props.data.permission.description,
});

const submit = () => {
  form.put(route('admin.permissions.update', props.data.permission.id), {
    onSuccess: () => emit('permissionUpdated'),
  });
};
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      {{ $t('edit.permission') }}
    </h2>
    <form
      class="space-y-4 mt-4 mb-10"
      @submit.prevent="submit"
    >
      <div>
        <InputLabel
          for="key"
          class="block text-sm font-medium text-gray-700"
          :value="$t('key')"
        />
        <TextInput
          id="key"
          v-model="form.key"
          type="text"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          autocomplete="key"
          :placeholder="$t('key')"
        />
      </div>
      <InputError
        class="mt-2"
        :message="form.errors.key"
      />

      <div>
        <InputLabel
          for="description"
          class="block text-sm font-medium text-gray-700"
          :value="$t('description')"
        />
        <TextInput
          id="description"
          v-model="form.description"
          type="text"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          autocomplete="description"
          :placeholder="$t('description')"
        />
      </div>
      <InputError
        class="mt-2"
        :message="form.errors.description"
      />

      <button
        type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200"
      >
        {{ $t('Save') }}
      </button>
    </form>
  </div>
</template>
