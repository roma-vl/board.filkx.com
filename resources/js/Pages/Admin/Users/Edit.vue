<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  user: { type: Object, required: true },
  roles: { type: Array, required: true },
  userRoles: { type: Array, required: true },
});

const emit = defineEmits(['userUpdated']);

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',
  active: !!props.user.email_verified_at,
  locale: props.user.locale,
  roles: [...props.userRoles],
});

watch(
  () => props.user,
  (newUser) => {
    if (newUser) {
      form.name = newUser.name;
      form.email = newUser.email;
      form.active = !!newUser.email_verified_at;
      form.locale = newUser.locale;
    }
  }
);

const submit = () => {
  form.put(route('admin.users.update', props.user.id), {
    onSuccess: () => {
      emit('userUpdated');
    },
  });
};
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      Edit User
    </h2>
    <form
      class="space-y-4 mt-4 mb-10"
      @submit.prevent="submit"
    >
      <div>
        <InputLabel
          for="name"
          :value="$t('name')"
        />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          :placeholder="$t('name')"
        />
        <InputError :message="form.errors.name" />
      </div>
      <div>
        <InputLabel
          for="email"
          :value="$t('email')"
        />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          :placeholder="$t('email')"
        />
        <InputError :message="form.errors.email" />
      </div>
      <div>
        <InputLabel
          for="password"
          :value="$t('password') + ' (' + $t('optional') + ')'"
        />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          :placeholder="$t('password')"
        />
        <InputError :message="form.errors.password" />
      </div>
      <div>
        <InputLabel
          for="active"
          :value="$t('status')"
        />
        <select
          id="active"
          v-model="form.active"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
        >
          <option :value="true">
            Active
          </option>
          <option :value="false">
            Inactive
          </option>
        </select>
        <InputError :message="form.errors.active" />
      </div>
      <div>
        <InputLabel
          for="locale"
          :value="$t('locale')"
        />
        <select
          id="locale"
          v-model="form.locale"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
        >
          <option value="en">
            English
          </option>
          <option value="uk">
            Українська
          </option>
        </select>
        <InputError :message="form.errors.locale" />
      </div>
      <div>
        <InputLabel
          for="roles"
          :value="$t('roles')"
        />
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
        <InputError :message="form.errors.roles" />
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
