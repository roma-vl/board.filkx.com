<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import Reject from '@/Pages/Admin/Advert/Actions/Reject.vue';
import Modal from '@/Components/Modal.vue';
import {
  getDateFormatFromLocale,
  getFullPathForAvatarImage,
  getFullPathForImage,
} from '@/helpers.js';
import axios from 'axios';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
const props = defineProps({
  advert: {
    type: Object,
    default: () => ({}),
  },
  categories: {
    type: Object,
    default: () => ({}),
  },
  locations: {
    type: Object,
    default: () => ({}),
  },
  values: {
    type: Array,
    default: () => [],
  },
  photos: {
    type: Array,
    default: () => [],
  },
  category: {
    type: Object,
    default: () => ({}),
  },
  isFavorited: {
    type: Boolean,
    default: false,
  },
  can: {
    type: Array,
    default: () => [],
  },
});
const user = usePage().props.auth.user;
const isLiked = ref(false);
const userPhone = ref(false);
const isRejectModalOpen = ref(false);
const advertId = ref(null);
const isMessengerOpen = ref(false);
const messages = ref({
  data: [],
  current_page: 1,
  last_page: 1,
});
const dialogId = ref(null);

const loadMessages = async (page = 1) => {
  isMessengerOpen.value = true;
  try {
    const dialogResponse = await axios.get(route('account.chats.get.dialog', props.advert.id));
    dialogId.value = dialogResponse.data.id;
    const response = await axios.get(route('account.chats.messages', dialogId.value), {
      params: { page },
    });

    if (page === 1) {
      messages.value = response.data.messages;
    } else {
      messages.value.data = [...response.data.messages.data, ...messages.value.data];
      messages.value.current_page = response.data.messages.current_page;
      messages.value.last_page = response.data.messages.last_page;
    }
  } catch (error) {
    console.error(error);
  }
};

const toggleMessenger = async () => {
  if (isMessengerOpen.value) {
    isMessengerOpen.value = false;
  } else {
    await loadMessages();
  }
};

const toggleLike = () => {
  if (props.isFavorited === true) {
    router.delete(route('account.favorites.remove', { advert: props.advert.id }));
  } else {
    router.post(route('account.favorites.add', { advert: props.advert.id }));
  }
  isLiked.value = !isLiked.value;
};

const form = useForm({});
const isDraft = computed(() => props.advert.status === 'draft');
const isOnModeration = computed(() => props.advert.status === 'moderation');
const isActive = computed(() => props.advert.status === 'active');

const submitAction = (routeName) => {
  form.post(route(routeName, props.advert.id));
};

const getValue = (attributeName) => {
  const valueObj = props.values.find((v) => v.attribute === attributeName);
  return valueObj ? valueObj.value : '-';
};

const mainPhoto = ref(props.photos.length ? getFullPathForImage(props.photos[0].file) : '');

const setMainPhoto = (photo) => {
  mainPhoto.value = photo;
};

const getPhone = async (id) => {
  if (userPhone.value) return;
  try {
    const response = await axios.get(route('adverts.phone', id));
    userPhone.value = response.data;
  } catch (error) {
    console.error('Помилка при завантаженні номера користувача', error);
  }
};

const publish = async () => {
  router.post(route('account.adverts.actions.publish', { advert: props.advert.id }), {
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};

const toDraft = async () => {
  router.post(route('account.adverts.actions.draft', { advert: props.advert.id }), {
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};
const activate = async () => {
  router.post(route('admin.adverts.actions.moderation.active', { advert: props.advert.id }), {
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};

const rejectAdvert = () => {
  isRejectModalOpen.value = true;
  advertId.value = props.advert.id;
};
const deleteAdvert = () => {
  if (confirm('Ви впевнені, що хочете видалити оголошення?')) {
    router.delete(route('account.adverts.destroy', props.advert.id));
  }
};

const sendMessage = () => {
  const text = messageForm.message.trim();
  if (!text) return;

  messageForm.post(route('account.chats.store', props.advert.id), {
    onSuccess: () => {
      messages.value.data.unshift({
        id: Date.now(),
        message: text,
        user: {
          id: user?.id,
          avatar_url: user?.avatar_url,
        },
      });
      messageForm.reset('message');
      scrollToBottom();
    },
  });
};

const scrollToBottom = () => {};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    messages.value.messages.push({
      id: Date.now(),
      text: `📎 Файл: ${file.name}`,
      user: {
        id: user?.id,
        avatar_url: user?.avatar_url,
      },
    });
  }
};

const openEmojis = () => {
  console.log('🤪 Тут має бути emoji picker.');
};

const messageForm = useForm({
  message: '',
  advert_id: props.advert.id,
});
</script>

<template>
  <Head :title="advert.title" />
  <AuthenticatedLayout>
    <div class="py-6 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <Breadcrumbs
          class="mb-6"
          :categories="props.categories"
          :locations="props.locations"
        />

        <div
          v-can="['manage.own.advert', 'admin']"
          class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 rounded-xl p-4 shadow-md border border-indigo-100 dark:border-gray-700"
        >
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div
              v-can="'manage.own.advert'"
              class="flex flex-wrap gap-2"
            >
              <Link
                :href="route('account.adverts.edit', props.advert.id)"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors"
              >
                {{ $t('edit') }}
              </Link>
              <button
                v-if="isDraft"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="publish"
              >
                {{ $t('publish') }}
              </button>
              <button
                v-if="isActive"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="submitAction('adverts.adverts.close')"
              >
                {{ $t('close') }}
              </button>
              <button
                v-if="isOnModeration || isActive"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="toDraft"
              >
                {{ $t('to.draft') }}
              </button>
              <button
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="deleteAdvert"
              >
                {{ $t('delete') }}
              </button>
            </div>
            <div
              v-can="'admin'"
              class="flex flex-wrap gap-2"
            >
              <button
                v-if="isOnModeration"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="activate"
              >
                {{ $t('publish') }}
              </button>
              <button
                v-if="isOnModeration || isActive"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors"
                @click="rejectAdvert"
              >
                {{ $t('reject') }}
              </button>
            </div>
          </div>
        </div>

        <div
          v-if="isDraft"
          class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200"
        >
          {{ $t('status.draft') }}
        </div>
        <div
          v-if="isOnModeration"
          class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200"
        >
          {{ $t('status.on.moderation') }}
        </div>
        <div
          v-if="advert.reject_reason"
          class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-200"
        >
          <p class="font-semibold">
            {{ $t('status.rejected') }}
          </p>
          <p>{{ advert.reject_reason }}</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
          <div class="w-full lg:w-2/3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
              <div class="relative aspect-video">
                <img
                  :src="mainPhoto"
                  class="w-full h-full object-cover"
                  :alt="advert.title"
                >
              </div>
              <div
                v-if="props.photos.length > 1"
                class="p-4"
              >
                <div class="flex gap-2 overflow-x-auto pb-2">
                  <img
                    v-for="photo in props.photos"
                    :key="photo.id"
                    :src="getFullPathForImage(photo.file)"
                    alt=""
                    class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-indigo-500 transition-colors flex-shrink-0"
                    @click="setMainPhoto(getFullPathForImage(photo.file))"
                  >
                </div>
              </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mt-6 p-6">
              <div class="flex flex-wrap gap-2 mb-6">
                <span
                  v-for="item in values"
                  :key="item.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200"
                >
                  <span class="font-semibold mr-1">{{ item.attribute }}:</span>
                  {{ getValue(item.attribute) }}
                </span>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                {{ $t('description') }}
              </h3>
              <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                {{ advert.content }}
              </p>
              <div class="mt-6 flex justify-end">
                <button
                  type="button"
                  class="text-red-500 hover:text-red-700 font-medium transition-colors"
                >
                  {{ $t('report') }}
                </button>
              </div>
            </div>
          </div>
          <div class="w-full lg:w-1/3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sticky top-20">
              <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ $t('published') }}: {{ getDateFormatFromLocale(advert.created_at) }}
                </p>
                <button
                  type="button"
                  class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                  :class="{ 'text-red-500': isLiked, 'text-gray-400': !isLiked }"
                  :aria-label="isLiked ? $t('Remove from favorites') : $t('Add to favorites')"
                  @click="toggleLike"
                >
                  <HeartIcon
                    v-if="!isLiked"
                    class="h-6 w-6"
                  />
                  <HeartSolidIcon
                    v-else
                    class="h-6 w-6"
                  />
                </button>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                {{ $t('expires') }}: {{ getDateFormatFromLocale(advert.expires_at) }}
              </p>

              <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                {{ advert.title }}
              </h1>

              <div class="flex items-center mb-6">
                <h2 class="text-3xl font-bold text-green-600 dark:text-green-400">
                  {{ advert.price }} грн.
                </h2>
                <span
                  v-if="advert.is_negotiable"
                  class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300"
                >
                  {{ $t('negotiable') }}
                </span>
              </div>

              <!-- Actions -->
              <div class="space-y-4">
                <button
                  v-if="user?.id !== advert?.user?.id"
                  type="button"
                  class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors"
                  @click="toggleMessenger"
                >
                  {{ $t('messages') }}
                </button>
                <button
                  type="button"
                  class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
                  @click.prevent="getPhone(advert.id)"
                >
                  {{ userPhone ? userPhone : $t('show.phone') }}
                </button>
              </div>
            </div>

            <div
              class="mt-6 rounded-xl border border-yellow-300 bg-yellow-50 p-6 dark:border-yellow-600 dark:bg-yellow-900/20"
            >
              <div class="flex flex-col items-center text-center gap-4">
                <div class="text-gray-800 dark:text-gray-200 text-md font-medium">
                  🔥 {{ $t('advert.promote.title') }}
                </div>
                <Link
                  :href="route('account.adverts.promote', props.advert.id)"
                  class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800 transition-all"
                >
                  🚀 {{ $t('advert.promote.button') }}
                </Link>
              </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mt-6 p-6">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                {{ $t('user') }}
              </h3>
              <div class="flex items-center">
                <img
                  class="w-16 h-16 rounded-full object-cover"
                  :src="getFullPathForAvatarImage(advert.user?.avatar_url)"
                  :alt="advert.user?.name"
                >
                <div class="ml-4">
                  <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ advert.user?.name }}
                  </p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $t('registered.since') }}
                    {{ getDateFormatFromLocale(advert.user?.created_at) }}
                  </p>
                </div>
              </div>
              <div class="mt-6">
                <Link
                  :href="route('list.advert.user', advert.user?.id || 0)"
                  class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors"
                >
                  {{ $t('user.all.adverts') }}
                </Link>
              </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mt-6 p-6">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                {{ $t('location') }}
              </h3>
              <p class="text-gray-700 dark:text-gray-300 mb-4">
                {{ advert.region?.name }} {{ advert.address }}
              </p>
              <div
                class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden"
              >
                <!-- TODO: Replace with actual map component or static image -->
                <img
                  src="https://inweb.ua/blog/wp-content/uploads/2020/09/vstavte-etot-kod-na-svoyu-html-stranitsu-ili-vidzhet.jpg"
                  alt="Map placeholder"
                  class="w-full h-full object-cover"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <div
        v-if="isMessengerOpen"
        class="fixed bottom-4 right-4 w-full max-w-md h-[500px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[100] flex flex-col"
      >
        <div
          class="bg-indigo-600 text-white px-4 py-3 rounded-t-xl flex justify-between items-center"
        >
          <h4 class="font-semibold">
            {{ $t('chat.with.author') }}
          </h4>
          <button
            type="button"
            class="text-white text-xl font-bold leading-none"
            @click="toggleMessenger"
          >
            ×
          </button>
        </div>
        <div class="flex flex-col h-full bg-white dark:bg-gray-800">
          <div
            v-if="messages?.data.length"
            class="flex-1 overflow-y-auto p-4 space-y-4"
          >
            <div
              v-for="message in messages.data.slice().reverse()"
              :key="message.id"
              class="flex"
              :class="message.user.id === user.id ? 'justify-end' : 'justify-start'"
            >
              <div
                class="flex items-end gap-2"
                :class="message.user.id === user.id ? 'flex-row-reverse' : ''"
              >
                <img
                  class="w-8 h-8 rounded-full object-cover"
                  :src="getFullPathForAvatarImage(message.user.avatar_url)"
                  :alt="$t('Avatar')"
                >
                <div
                  class="px-4 py-2 rounded-lg max-w-xs break-words"
                  :class="
                    message.user.id === user.id
                      ? 'bg-indigo-100 dark:bg-indigo-900/30 text-right'
                      : 'bg-gray-100 dark:bg-gray-700 text-left'
                  "
                >
                  {{ message.message }}
                </div>
              </div>
            </div>
          </div>
          <div
            v-else
            class="flex-1 overflow-y-auto p-4 text-center text-gray-500 dark:text-gray-400"
          >
            {{ $t('no.messages.yet') }}
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 p-3 flex items-center gap-2">
            <form
              class="flex-1 flex items-center gap-2"
              @submit.prevent="sendMessage"
            >
              <button
                type="button"
                class="text-gray-500 hover:text-yellow-500 dark:text-gray-400 dark:hover:text-yellow-400 transition-colors"
                :title="$t('emojis')"
                @click="openEmojis"
              >
                😊
              </button>
              <label
                class="cursor-pointer text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                :title="$t('attach.file')"
              >
                <input
                  type="file"
                  class="hidden"
                  @change="handleFileUpload"
                >
                📎
              </label>
              <input
                v-model="messageForm.message"
                type="text"
                :placeholder="$t('write.message')"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-colors"
              >
              <button
                type="submit"
                :title="$t('send')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
              >
                ⤊
              </button>
            </form>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Reject Modal -->
    <Modal
      :show="isRejectModalOpen"
      max-width="2xl"
      @close="isRejectModalOpen = false"
    >
      <Reject
        :advert-id="advertId"
        @reject-created="isRejectModalOpen = false"
      />
    </Modal>
  </AuthenticatedLayout>
</template>
