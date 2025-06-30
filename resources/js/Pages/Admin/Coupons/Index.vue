<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { getDateFormatFromLocale } from '@/helpers.js';

const coupons = computed(() => usePage().props.coupons.data);
const pagination = computed(() => usePage().props.coupons);

const modalOpen = ref(false);
const form = ref({
  id: null,
  code: '',
  discount_type: 'fixed',
  discount_amount: 0,
  applicable_services: [],
  usage_limit: null,
  expires_at: null,
});

const isEditing = computed(() => form.value.id !== null);

const headings = [
  { key: 'id', value: 'ID' },
  { key: 'code', value: 'Код' },
  { key: 'discount_type', value: 'Тип' },
  { key: 'discount_amount', value: 'Знижка' },
  { key: 'usage_limit', value: 'Ліміт' },
  { key: 'used_count', value: 'Використано' },
  { key: 'expires_at', value: 'Діє до' },
  { key: 'actions', value: '' },
];

const routes = [
  { key: 'index', value: 'admin.coupons.index' },
  { key: 'search', value: 'admin.coupons.search' },
];

const openCreateModal = () => {
  form.value = {
    id: null,
    code: '',
    discount_type: 'fixed',
    discount_amount: 0,
    applicable_services: [],
    usage_limit: null,
    expires_at: null,
  };
  modalOpen.value = true;
};

const openEditModal = (coupon) => {
  form.value = { ...coupon };
  form.value.expires_at = modalOpen.value = true;
};

const submit = () => {
  if (isEditing.value) {
    router.put(route('admin.coupons.update', form.value.id), form.value, {
      onSuccess: () => (modalOpen.value = false),
    });
  } else {
    router.post(route('admin.coupons.store'), form.value, {
      onSuccess: () => (modalOpen.value = false),
    });
  }
};

const destroyCoupon = (id) => {
  if (confirm('Видалити купон?')) {
    router.delete(route('admin.coupons.destroy', id));
  }
};
</script>

<template>
  <AdminLayout title="Купони">
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">
          Купони
        </h1>
        <button
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
          @click="openCreateModal"
        >
          Додати
        </button>
      </div>

      <Grid
        :items="coupons"
        :pagination="pagination"
        :headings="headings"
        routes=""
      >
        <template #column-expires_at="{ row }">
          {{ getDateFormatFromLocale(row.expires_at) ?? '—' }}
        </template>

        <template #column-actions="{ row }">
          <div class="flex gap-2">
            <button
              class="text-blue-500"
              @click="() => openEditModal(row)"
            >
              <PencilIcon />
            </button>
            <button
              class="text-red-500"
              @click="() => destroyCoupon(row.id)"
            >
              <TrashIcon />
            </button>
          </div>
        </template>
      </Grid>

      <Modal
        :show="modalOpen"
        @close="modalOpen = false"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold mb-4">
            {{ isEditing ? 'Редагувати купон' : 'Новий купон' }}
          </h2>

          <form
            class="space-y-4"
            @submit.prevent="submit"
          >
            <div>
              <label class="block font-medium">Код</label>
              <input
                v-model="form.code"
                class="w-full border px-3 py-2 rounded"
                required
              >
            </div>

            <div>
              <label class="block font-medium">Тип знижки</label>
              <select
                v-model="form.discount_type"
                class="w-full border px-3 py-2 rounded"
              >
                <option value="fixed">
                  Фіксована
                </option>
                <option value="percent">
                  Відсоткова
                </option>
              </select>
            </div>

            <div>
              <label class="block font-medium">Сума знижки</label>
              <input
                v-model.number="form.discount_amount"
                type="number"
                step="0.01"
                class="w-full border px-3 py-2 rounded"
              >
            </div>

            <div>
              <label class="block font-medium">Ліміт використань</label>
              <input
                v-model.number="form.usage_limit"
                type="number"
                class="w-full border px-3 py-2 rounded"
              >
            </div>

            <div>
              <label class="block font-medium">Дійсний до</label>
              <input
                v-model="form.expires_at"
                type="date"
                class="w-full border px-3 py-2 rounded"
              >
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded"
              >
                Зберегти
              </button>
            </div>
          </form>
        </div>
      </Modal>
    </div>
  </AdminLayout>
</template>
