<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineEmits } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
});
const form = useForm({
  name: '',
  email: '',
  password: '',
  roles: [],
});

const emit = defineEmits(['userCreated']);

const submit = () => {
  form.post(route('admin.users.store'), {
    onSuccess: () => {
      emit('userCreated');
    },
  });
};
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      {{ $t('new.user') }}
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
          :placeholder="$t('name')"
        />
      </div>
      <InputError
        class="mt-2"
        :message="form.errors.name"
      />

      <div>
        <InputLabel
          for="email"
          class="block text-sm font-medium text-gray-700"
          :value="$t('email')"
        />
        <TextInput
          id="email"
          v-model="form.email"
          type="text"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          autocomplete="email"
          :placeholder="$t('email')"
        />
        <InputError
          class="mt-2"
          :message="form.errors.email"
        />
      </div>

      <div>
        <InputLabel
          for="password"
          class="block text-sm font-medium text-gray-700"
          :value="$t('password')"
        />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          autocomplete="password"
          :placeholder="$t('password')"
        />
        <InputError
          class="mt-2"
          :message="form.errors.password"
        />
      </div>

      <div>
        <label for="roles">{{ $t('roles') }}: </label>
        <select
          v-model="form.roles"
          class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          multiple
        >
          <option
            v-for="role in props.roles"
            :key="role.id"
            class="w-full mt-1 p-2 border-0 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
            :value="role.id"
          >
            {{ role.name }}
          </option>
        </select>
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
