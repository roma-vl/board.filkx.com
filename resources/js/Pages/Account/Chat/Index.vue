<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed, nextTick, onMounted, ref } from 'vue';
import {
  getDateFormatFromLocale,
  getFullPathForAvatarImage,
  getFullPathForImage,
} from '@/helpers.js';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const dialogs = computed(() => usePage().props.dialogs);
const me = usePage().props.auth.user;

const newMessage = ref('');
const activeDialog = ref(null);
const messages = ref([]);
const page = ref(1);
const hasMore = ref(true);
const isLoading = ref(false);
const activeDialogId = usePage().props.activeDialogId;

const selectDialog = async (dialog) => {
  activeDialog.value = dialog;
  messageForm.advert_id = dialog.advert_id; // 🔥 Тут
  messageForm.client_id = dialog.client_id; // Якщо треба
  messages.value = [];
  page.value = 1;
  hasMore.value = true;

  await loadMessages(dialog.id);
  await nextTick(() => scrollToBottom());
};

const loadMessages = async (dialogId) => {
  if (isLoading.value || !hasMore.value) return;
  isLoading.value = true;

  const { data } = await axios.get(route('account.chats.messages', dialogId), {
    params: { page: page.value },
  });

  if (page.value === 1) {
    messages.value = data.messages.data.reverse();
  } else {
    messages.value = [...data.messages.data.reverse(), ...messages.value];
  }

  hasMore.value = data.messages.next_page_url !== null;
  page.value++;
  isLoading.value = false;
};

const scrollToBottom = () => {
  const container = document.querySelector('#messages');
  if (container) container.scrollTop = container.scrollHeight;
};

const onScrollTop = (e) => {
  const container = e.target;
  if (container.scrollTop === 0) {
    loadMessages(activeDialog.value.id);
  }
};

const sendMessage = () => {
  const text = messageForm.message.trim();
  if (!text || !messageForm.advert_id) return;

  messageForm.post(route('account.chats.store', messageForm.advert_id), {
    onSuccess: () => {
      messages.value.push({
        id: Date.now(),
        message: text,
        user_id: me.id,
        user: {
          avatar_url: me.avatar_url,
        },
      });
      scrollToBottom();
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

onMounted(() => {
  const url = new URL(window.location.href);
  const id = url.pathname.split('/').pop();
  const dialog = dialogs.value.find((d) => d.id == id);
  if (dialog) selectDialog(dialog);
});

onMounted(() => {
  if (activeDialogId) {
    const dialog = dialogs.value.find((d) => d.id == activeDialogId);
    if (dialog) selectDialog(dialog);
  }
});
</script>

<template>
  <Head :title="t('chat.title')" />
  <AuthenticatedLayout>
    <div class="py-4 px-6 max-w-7xl mx-auto">
      <div class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md">
        <ProfileMenu :active-tab="'account.chats.index'" />
        <div class="grid grid-cols-3 gap-6">
          <!-- Ліва колонка: список діалогів -->
          <div class="col-span-1">
            <div class="h-[600px] overflow-y-auto space-y-4 p-2">
              <div
                v-for="chat in dialogs"
                :key="chat.id"
                class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow cursor-pointer hover:bg-violet-100 dark:hover:bg-gray-700"
                :class="{ 'ring-2 ring-violet-500': chat.id === activeDialog?.id }"
                @click="router.visit(route('account.chats.show', chat.id))"
              >
                <div class="flex items-center space-x-3">
                  <img
                    class="w-12 h-12 rounded-lg object-cover"
                    :src="getFullPathForImage(chat?.advert?.first_photo?.file)"
                    alt=""
                  >
                  <div>
                    <p class="font-semibold">
                      {{ chat.client.name }}
                    </p>
                    <p class="text-sm text-gray-500">
                      {{ chat.advert.title }}
                    </p>
                  </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                  {{ getDateFormatFromLocale(chat.messages[0]?.created_at) }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                  {{ chat.messages[0]?.message }}
                </p>
              </div>
            </div>
          </div>

          <!-- Права колонка: активний чат -->
          <div class="col-span-2">
            <div
              v-if="activeDialog"
              class=""
            >
              <div
                class="flex items-center space-x-4 mb-4 bg-white dark:bg-gray-700 rounded-lg shadow p-6"
              >
                <img
                  class="w-14 h-14 rounded-full object-cover"
                  :src="getFullPathForAvatarImage(activeDialog?.client.avatar_url)"
                  alt=""
                >
                <div>
                  <h3 class="text-lg font-bold">
                    {{ activeDialog?.client.name }}
                  </h3>
                  <p class="text-sm text-gray-500">
                    {{ activeDialog?.advert?.title }} — {{ activeDialog?.advert?.status }}
                  </p>
                </div>
              </div>

              <!-- Повідомлення -->
              <div
                id="messages"
                class="h-[415px] overflow-y-auto space-y-4 pr-2 bg-white dark:bg-gray-700 rounded-lg shadow p-6"
                @scroll.passive="onScrollTop"
              >
                <button
                  v-if="hasMore && activeDialog?.id"
                  class="text-xs text-blue-600 hover:underline"
                  @click="loadMessages(activeDialog.id)"
                >
                  {{ t('chat.loadMore') }}
                </button>

                <div
                  v-for="message in messages || []"
                  :key="message?.id"
                  class="flex"
                  :class="{
                    'justify-end': message.user_id === me.id,
                    'justify-start': message.user_id !== me.id,
                  }"
                >
                  <div
                    class="flex items-start space-x-2 max-w-[70%]"
                    :class="message.user_id === me.id ? 'flex-row-reverse text-right' : ''"
                  >
                    <img
                      class="w-10 h-10 rounded-full object-cover"
                      :src="getFullPathForAvatarImage(message?.user?.avatar_url)"
                      alt=""
                    >
                    <div
                      class="px-4 py-2 rounded-lg"
                      :class="
                        message.user_id === me.id
                          ? 'bg-violet-600 text-white'
                          : 'bg-gray-200 dark:bg-gray-800 dark:text-white'
                      "
                    >
                      {{ message.message }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Форма відправки -->
              <form
                class="flex items-center space-x-2 mt-4"
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
                  class="flex-1 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-600 dark:bg-gray-800 dark:border-gray-900"
                  :placeholder="t('chat.placeholder')"
                  @keyup.enter="sendMessage"
                >

                <button
                  type="submit"
                  class="px-6 h-12 bg-violet-600 text-white rounded-lg hover:bg-violet-700"
                >
                  {{ t('chat.send') }}
                </button>
              </form>
            </div>

            <div
              v-else
              class="text-center text-gray-500 mt-10"
            >
              {{ t('chat.select') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
