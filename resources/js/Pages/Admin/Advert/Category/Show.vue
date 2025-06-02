<script setup>
import { defineProps, ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Admin/Advert/Category/Attribute/Create.vue';
import Edit from '@/Pages/Admin/Advert/Category/Attribute/Edit.vue';

const props = defineProps({
  category: {
    type: Object,
    default: () => ({}),
  },
  attributes: {
    type: Array,
    default: () => [],
  },
  parentAttributes: {
    type: Array,
    default: () => [],
  },
});
const isCreateModalOpen = ref(false);
const selectedCategory = ref(null);
const isEditModalOpen = ref(false);

const openCreateModal = async () => {
  const { data } = await axios.get(
    route('admin.adverts.category.attributes.create', props.category.id)
  );
  selectedCategory.value = data;
  isCreateModalOpen.value = true;
};

const openEditModal = async (attributeId) => {
  const { data } = await axios.get(
    route('admin.adverts.category.attributes.edit', {
      category: props.category.id,
      attribute: attributeId,
    })
  );
  selectedCategory.value = data;
  isEditModalOpen.value = true;
};

const refreshAttributes = () => {
  router.get(route('admin.adverts.category.show', { category: props.category.id }), {
    preserveScroll: true,
    onSuccess: () =>
      router.replace(route('admin.adverts.category.show', { category: props.category.id })),
  });
};

const deleteCategory = (attributeId) => {
  if (confirm('Are you sure you want to delete this Category?')) {
    router.delete(
      route('admin.adverts.category.attributes.destroy', {
        category: props.category.id,
        attribute: attributeId,
      }),
      {
        preserveScroll: true,
        onSuccess: () =>
          router.replace(route('admin.adverts.category.show', { category: props.category.id })),
      }
    );
  }
};
</script>

<template>
  <Head :title="$t('category') + '-' + $t('attribute')" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px] dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
        >
          <h1 class="text-2xl font-bold">
            {{ $t('category') }}: {{ props.category.name }}
          </h1>
          <p class="text-gray-600 dark:text-gray-200">
            {{ $t('slug') }}: {{ props.category.slug }}
          </p>

          <div class="mb-2 flex justify-end">
            <button
              class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
              @click="openCreateModal"
            >
              + {{ $t('add.attribute') }}
            </button>
          </div>
          <p>{{ $t('parents') }}</p>
          <table class="w-full mt-4 border-collapse border border-gray-200 dark:border-gray-900">
            <thead>
              <tr class="bg-gray-100 dark:bg-gray-900">
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('ID') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('name') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('type') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('required') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('sort') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="attr in parentAttributes"
                :key="attr.id"
                class="dark:bg-gray-800 dark:hover:bg-gray-700"
              >
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.id }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.name }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.type }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.required ? 'Так' : 'Ні' }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.sort }}
                </td>
              </tr>
            </tbody>
          </table>

          <p>{{ $t('own') }}</p>
          <table class="w-full mt-4 border-collapse border border-gray-200 dark:border-gray-900">
            <thead>
              <tr class="bg-gray-100 dark:bg-gray-900">
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('ID') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('name') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  Тип
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('required') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('sort') }}
                </th>
                <th class="border dark:border-gray-700 p-2">
                  {{ $t('actions') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="attr in attributes"
                :key="attr.id"
                class="dark:bg-gray-800 dark:hover:bg-gray-700"
              >
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.id }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.name }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.type }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.required ? $t('yes') : $t('no') }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  {{ attr.sort }}
                </td>
                <td class="border dark:border-gray-700 p-2">
                  <button
                    class="text-blue-500 pr-2 hover:underline"
                    @click.stop="openEditModal(attr.id)"
                  >
                    {{ $t('edit') }}
                  </button>
                  <button
                    class="text-red-500 hover:underline"
                    @click.stop="deleteCategory(attr.id)"
                  >
                    {{ $t('delete') }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Modal
      :show="isCreateModalOpen"
      max-width="2xl"
      @close="isCreateModalOpen = false"
    >
      <Create
        :data="selectedCategory"
        @attribute-created="refreshAttributes"
      />
    </Modal>

    <Modal
      :show="isEditModalOpen"
      max-width="2xl"
      @close="isEditModalOpen = false"
    >
      <Edit
        :data="selectedCategory"
        @attribute-updated="refreshAttributes"
      />
    </Modal>
  </AdminLayout>
</template>
