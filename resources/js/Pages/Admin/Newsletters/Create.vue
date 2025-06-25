<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = reactive({
  title: '',
  body: { uk: '', en: '' },
  scheduled_at: null,
  html: '',
});

function submit() {
  router.post(route('admin.newsletters.store'), form);
}
</script>

<template>
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-900 dark:text-gray-200"
        >
          <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">
              Нова розсилка
            </h1>
            <form @submit.prevent="submit">
              <div>
                <label class="font-medium">Заголовок</label>
                <input
                  v-model="form.title"
                  class="input w-full"
                  required
                >
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="font-medium">Текст (укр)</label>
                  <textarea
                    v-model="form.body.uk"
                    class="textarea w-full h-40"
                    required
                  />
                </div>
                <div>
                  <label class="font-medium">Text (en)</label>
                  <textarea
                    v-model="form.body.en"
                    class="textarea w-full h-40"
                    required
                  />
                </div>
              </div>
              <div>
                <label class="font-medium">HTML контент (опціонально)</label>
                <textarea
                  v-model="form.html"
                  class="textarea w-full h-60 font-mono"
                  placeholder="Встав сюди свій HTML шаблон"
                />
              </div>

              <div>
                <label class="font-medium">Запланувати</label>
                <input
                  v-model="form.scheduled_at"
                  type="datetime-local"
                  class="input w-full"
                >
              </div>
              <button class="bg-blue-500 p-3 rounded mt-4">
                💌 Створити
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
