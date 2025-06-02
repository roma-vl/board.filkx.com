<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getDateFormatFromLocale } from '@/helpers.js';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
const props = defineProps({
  ticket: {
    type: Object,
    default: () => ({}),
  },
  messages: {
    type: Object,
    default: () => ({}),
  },
  statuses: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  message: '',
});
function submitComment() {
  if (!form.message.trim()) return;
  form.post(route('account.tickets.add.message', props.ticket.id), {
    preserveScroll: true,
  });
  form.message = '';
}
</script>

<template>
  <Head :title="ticket.subject" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
        <div class="list-disc list-inside text-gray-800">
          <span class="underline cursor-pointer"> {{ $t('main') }} </span>
        </div>
      </div>
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50">
        <div class="flex gap-6">
          <div class="w-2/3">
            <div class="bg-white rounded-xl shadow p-6 space-y-4 dark:bg-gray-700">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-200">
                {{ ticket.subject }}
              </h1>
              <p class="text-gray-700 leading-relaxed whitespace-pre-line dark:text-gray-300">
                {{ ticket.content }}
              </p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 mt-6 dark:bg-gray-700">
              <p class="text-gray-900 text-lg font-bold mb-2 dark:text-gray-200">
                {{ $t('comments') }}
              </p>
              <div class="border-b border-gray-200 mb-4" />
              <div
                v-if="messages.length"
                class="space-y-6"
              >
                <div
                  v-for="(msg, index) in messages"
                  :key="index"
                  class="flex items-start gap-4 dark:bg-gray-800 p-3 rounded-md shadow-md"
                >
                  <img
                    :src="
                      msg.user.avatar_url || 'https://ui-avatars.com/api/?name=' + msg.user.name
                    "
                    class="w-16 h-16 rounded-full object-cover border border-gray-300"
                    :alt="msg.user.name"
                  >
                  <div class="flex-1">
                    <div class="flex justify-between items-center">
                      <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">
                        {{ msg.user.name }}
                      </span>
                      <span class="text-xs text-gray-500 dark:text-gray-200">
                        {{ getDateFormatFromLocale(msg.created_at) }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line dark:text-gray-400">
                      {{ msg.message }}
                    </p>
                  </div>
                </div>
              </div>
              <div
                v-else
                class="text-gray-500 text-sm italic"
              >
                {{ $t('no.comments.yet') }}
              </div>
              <form
                class="mt-6"
                @submit.prevent="submitComment"
              >
                <p class="text-gray-900 text-lg font-bold mb-2 dark:text-gray-300">
                  {{ $t('leave.comment') }}
                </p>
                <textarea
                  v-model="form.message"
                  rows="4"
                  class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none resize-y dark:bg-gray-800 dark:border-gray-800"
                  :placeholder="$t('enter.comment')"
                />
                <div class="flex justify-end mt-3">
                  <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition"
                  >
                    {{ $t('send') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="w-1/3">
            <div class="rounded-lg shadow p-3 bg-white dark:bg-gray-700">
              <p class="mt-4 text-gray-800 text-sm dark:text-gray-200">
                {{ $t('published') }}: {{ getDateFormatFromLocale(ticket.created_at) }}
              </p>
              <div class="rounded-lg shadow p-3 bg-white mt-4 dark:bg-gray-800">
                <p class="text-gray-800 font-bold text-sm mb-4 dark:text-gray-200">
                  {{ $t('statuses') }}
                </p>
                <ul class="space-y-2">
                  <li
                    v-for="(status, index) in statuses"
                    :key="index"
                    class="flex justify-between items-center"
                  >
                    <span
                      class="m-1 inline-block px-3 py-1 rounded-full text-sm font-semibold"
                      :class="{
                        'bg-green-100 text-green-800': status.status === 'open',
                        'bg-green-200 text-green-700': status.status === 'processing',
                        'bg-yellow-100 text-yellow-800': status.status === 'pending',
                        'bg-red-100 text-red-800': status.status === 'closed',
                      }"
                    >
                      {{ $t(status.status) }}
                    </span>
                    <span class="text-gray-500 text-xs dark:text-gray-200">
                      {{ getDateFormatFromLocale(status.created_at) }}
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
