<script setup>
import { ref, defineEmits, defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const emit = defineEmits(['roleCreated']);

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});
const form = useForm({
  name: '',
  is_enabled: true,
  permissions: [],
});

const permissions = ref(props.data.permissions);

const submit = () => {
  form.post(route('admin.roles.store'), {
    onSuccess: () => emit('roleCreated'),
  });
};
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      {{ $t('new.role') }}
    </h2>
    <form
      class="space-y-4 mt-4 mb-10"
      @submit.prevent="submit"
    >
      <div>
        <InputLabel
          for="name"
          class="block text-sm font-medium text-gray-700"
          :value="$t('name')"
        />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          autocomplete="name"
          :placeholder="$t('role')"
        />
      </div>
      <InputError
        class="mt-2"
        :message="form.errors.name"
      />

      <InputLabel
        for="is_enabled"
        class="w-full mt-1 p-2 block text-sm font-medium text-gray-700"
        :value="$t('activate')"
      />
      <input
        id="is_enabled"
        v-model="form.is_enabled"
        type="checkbox"
      >

      <InputLabel
        for="permissions"
        class="block text-sm font-medium text-gray-700"
        :value="$t('permissions')"
      />
      <div
        v-for="permission in permissions"
        :key="permission.id"
        class="w-full mt-1 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
      >
        <input
          v-model="form.permissions"
          class="mr-3"
          type="checkbox"
          :value="permission.id"
        >
        {{ permission.key }}
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200"
      >
        {{ $t('Save') }}
      </button>
    </form>
  </div>
</template>
