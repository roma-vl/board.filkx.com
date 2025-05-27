<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed, ref } from 'vue';
import { getDateFormatFromLocale } from '@/helpers.js';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const dialogs = computed(() => usePage().props.dialogs);
const user = usePage().props.auth.user;

const newMessage = ref('');
const activeDialog = ref(null);

const selectDialog = (dialog) => {
  activeDialog.value = dialog;
  messageForm.advert_id = dialog.advert_id;
  messageForm.client_id = dialog.client_id;
};

const sendMessage = () => {
  const text = messageForm.message.trim();
  if (!text || !messageForm.advert_id) return;

  messageForm.post(route('account.chats.store', messageForm.advert_id), {
    onSuccess: () => {
      if (activeDialog.value?.messages) {
        activeDialog.value.messages.push({
          id: Date.now(),
          message: text,
          isMine: true,
        });
      }
      newMessage.value = '';
      messageForm.reset('message');
    },
  });
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    activeDialog.value.messages.push({
      id: Date.now(),
      message: t('chat.file', { fileName: file.name }),
      isMine: true,
    });
  }
};

const openEmojis = () => {
  alert('🤪 Тут має бути emoji picker.');
};

const messageForm = useForm({
  message: '',
  advert_id: '',
  client_id: '',
});
</script>

<template>
  <Head :title="t('chat.title')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <ProfileMenu :active-tab="'account.chats.index'" />
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-1 p-4 border-r border-gray-200">
              <h2 class="text-xl font-bold mb-4">
                {{ t('chat.list') }}
              </h2>
              <div
                v-for="chat in dialogs"
                :key="chat.id"
                class="mb-3 cursor-pointer"
                @click="selectDialog(chat)"
              >
                <div class="block p-3 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100">
                  <div class="flex flex-row justify-between">
                    <p class="font-medium text-md">
                      {{ chat.client.name }}
                    </p>
                    <p class="text-xs text-gray-400 text-right">
                      {{ getDateFormatFromLocale(chat.messages[0]?.created_at) }}
                    </p>
                  </div>
                  <p class="text-sm text-gray-600">
                    {{ chat.messages[0]?.message }}
                  </p>
                </div>
              </div>
            </div>

            <div class="col-span-2 p-4">
              <h2 class="text-xl font-bold mb-4">
                {{ t('chat.withSeller') }}
              </h2>
              <div
                v-if="activeDialog"
                class="space-y-4 mb-4"
              >
                <div
                  v-for="message in activeDialog?.messages || []"
                  :key="message.id"
                  class="flex items-start space-x-2"
                >
                  <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                    <img
                      :src="message.user.avatar_url"
                      alt=""
                    >
                  </div>
                  <div class="flex-1 p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="font-medium">
                      {{ message.message }}
                    </p>
                  </div>
                </div>
                <div class="mt-4">
                  <form
                    class="flex"
                    @submit.prevent="sendMessage"
                  >
                    <button
                      class="text-gray-500 hover:text-yellow-400 transition"
                      :title="t('chat.smileys')"
                      @click="openEmojis"
                    >
                      😊
                    </button>

                    <label class="cursor-pointer text-gray-500 hover:text-blue-500">
                      📎
                      <input
                        type="file"
                        class="hidden"
                        @change="handleFileUpload"
                      >
                    </label>

                    <input
                      v-model="messageForm.message"
                      type="text"
                      class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-600"
                      :placeholder="t('chat.placeholder')"
                      @keyup.enter="sendMessage"
                    >

                    <button
                      type="submit"
                      class="ml-2 w-40 h-16 bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700"
                    >
                      {{ t('chat.send') }}
                    </button>
                  </form>
                </div>
              </div>
              <div v-else>
                {{ t('chat.select') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
