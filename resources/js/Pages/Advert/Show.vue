<script setup>
import { computed, ref } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
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
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
        <div
          v-can="['manage.own.advert', 'admin']"
          class="bg-violet-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-6 p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <div
            v-can="'manage.own.advert'"
            class="flex flex-wrap gap-2 w-full sm:w-auto"
          >
            <a
              :href="route('account.adverts.edit', props.advert.id)"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded"
            >
              {{ $t('edit') }}
            </a>
            <button
              v-if="isDraft"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="publish"
            >
              {{ $t('publish') }}
            </button>
            <button
              v-if="isActive"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="submitAction('adverts.adverts.close')"
            >
              {{ $t('close') }}
            </button>
            <button
              v-if="isOnModeration || isActive"
              class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded"
              @click="toDraft"
            >
              {{ $t('to.draft') }}
            </button>
            <button
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
              @click="deleteAdvert"
            >
              {{ $t('delete') }}
            </button>
          </div>
          <div
            v-can="'admin'"
            class="flex flex-wrap gap-2 w-full sm:w-auto"
          >
            <button
              v-if="isOnModeration"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
              @click="activate"
            >
              {{ $t('publish') }}
            </button>
            <button
              v-if="isOnModeration || isActive"
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
              @click="rejectAdvert"
            >
              {{ $t('reject') }}
            </button>
          </div>
        </div>

        <Breadcrumbs
          class=""
          :categories="props.categories"
          :locations="props.locations"
        />
      </div>
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white-50">
        <div
          v-if="isDraft"
          class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4"
        >
          {{ $t('status.draft') }}
        </div>
        <div
          v-if="isOnModeration"
          class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4"
        >
          {{ $t('status.on.moderation') }}
        </div>
        <div
          v-if="advert.reject_reason"
          class="bg-red-100 text-red-800 p-3 rounded mb-4"
        >
          {{ $t('status.rejected') }}: {{ advert.reject_reason }}
        </div>
        <div class="flex flex-col lg:flex-row gap-6">
          <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow p-3 dark:bg-gray-700">
              <div
                class="w-full h-[300px] md:h-[400px] lg:h-[600px] flex justify-center items-center"
              >
                <img
                  :src="mainPhoto"
                  class="w-full h-full object-contain"
                  alt=""
                >
              </div>
              <div class="flex gap-2 mt-3 overflow-x-auto">
                <img
                  v-for="photo in props.photos"
                  :key="photo.id"
                  :src="getFullPathForImage(photo.file)"
                  alt=""
                  class="w-24 h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-blue-500 transition"
                  @click="setMainPhoto(getFullPathForImage(photo.file))"
                >
              </div>
            </div>
            <div class="bg-white rounded-lg shadow p-3 mt-5 dark:bg-gray-700">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="item in values"
                  :key="item.id"
                  class="border border-gray-700 px-4 py-1 font-medium text-gray-700 rounded-md cursor-pointer dark:text-gray-200 dark:border-gray-400"
                >
                  {{ item.attribute }} : {{ getValue(item.attribute) }}
                </span>
              </div>
              <p class="mt-4 text-gray-900 text-lg font-bold dark:text-gray-200">
                {{ $t('description') }}
              </p>
              <p class="whitespace-pre-line mt-4 text-gray-800 dark:text-gray-200">
                {{ advert.content }}
              </p>

              <div class="my-4 border border-b-1 mx-3" />
              <div class="flex justify-end">
                <button class="hover:underline hover:text-red-600 text-red-400 px-4 py-2 rounded">
                  {{ $t('report') }}
                </button>
              </div>
            </div>
          </div>
          <div class="w-full lg:w-1/3">
            <div class="rounded-lg shadow p-3 bg-white dark:bg-gray-700">
              <p class="mt-4 text-gray-800 text-sm dark:text-gray-200">
                {{ $t('published') }} {{ getDateFormatFromLocale(advert.created_at) }}
              </p>
              <p class="mt-4 text-gray-800 text-sm dark:text-gray-200">
                {{ $t('expires') }} {{ getDateFormatFromLocale(advert.expires_at) }}
              </p>
              <button
                class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition"
                @click="toggleLike"
              >
                <HeartIcon
                  v-if="!props.isFavorited"
                  class="w-6 h-6"
                />
                <HeartSolidIcon
                  v-else
                  class="w-6 h-6 text-red-500"
                />
              </button>
              <h1 class="p-2 text-2xl font-bold text-gray-900 dark:text-gray-300">
                {{ advert.title }}
              </h1>
              <div class="mt-4 p-2 flex flex-row items-center">
                <h2 class="text-2xl font-bold text-green-600">
                  {{ advert.price }} грн.
                </h2>
                <span class="pt-2 text-gray-800 text-sm pl-2 dark:text-gray-200">
                  {{ $t('negotiable') }}
                </span>
              </div>
              <button
                v-if="user?.id !== advert?.user?.id"
                class="h-14 rounded-md border-2 hover:border-[5px] hover:bg-white dark:hover:bg-gray-700 dark:text-gray-200 hover:text-blue-500 border-blue-500 bg-blue-500 w-full mt-5 mb-5 text-neutral-50 after:absolute after:left-0 after:top-0 after:-z-10 after:h-full after:w-full after:rounded-md"
                @click="toggleMessenger"
              >
                <span class="text-lg font-bold"> {{ $t('messages') }} </span>
              </button>
              <button
                class="h-14 rounded-md border-2 hover:border-[5px] border-blue-500 w-full mb-5 text-blue-500 after:absolute after:left-0 after:top-0 after:-z-10 after:h-full after:w-full after:rounded-md"
                @click.prevent="getPhone(advert.id)"
              >
                <span class="text-lg font-bold dark:text-gray-200">
                  {{ userPhone ? userPhone : $t('show.phone') }}
                </span>
              </button>
            </div>

            <div
              class="mt-6 rounded-xl border border-yellow-300 bg-yellow-50 p-6 dark:border-yellow-500 dark:bg-gray-800"
            >
              <div class="flex flex-col gap-4">
                <div
                  class="text-gray-800 text-lg font-medium dark:text-gray-100 text-center md:text-left"
                >
                  🔥 Хочете, щоб більше людей побачили ваше оголошення?
                </div>

                <div class="mt-2">
                  <Link
                    :href="route('account.adverts.promote', props.advert.id)"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-blue-600 bg-blue-500 px-6 py-3 text-white text-base font-semibold transition hover:bg-transparent hover:text-blue-600 dark:bg-blue-500 dark:hover:bg-transparent dark:hover:text-blue-400"
                  >
                    🚀 Просувати
                  </Link>
                </div>
              </div>
            </div>

            <div class="rounded-lg shadow p-3 bg-white mt-5 dark:bg-gray-700">
              <p class="font-bold pb-3 p-2 dark:text-gray-200">
                {{ $t('user') }}
              </p>
              <div class="flex flex-row p-2">
                <img
                  class="w-16 h-16 rounded-full"
                  :src="getFullPathForAvatarImage(advert.user?.avatar_url)"
                  alt=""
                >
                <div class="pl-4">
                  <p class="text-gray-600 mt-1 text-lg font-bold dark:text-gray-200">
                    {{ advert.user?.name + ' ' + advert.user?.first_name }}
                  </p>
                  <p class="text-gray-600 mt-1 dark:text-gray-400 text-sm">
                    {{ $t('registered.since') }}
                    {{ getDateFormatFromLocale(advert.user?.created_at) }}
                  </p>
                </div>
              </div>
              <div class="my-4 border border-b-1 mx-3" />
              <div class="flex items-center justify-center">
                <a
                  :href="route('list.advert.user', advert.user?.id || 0)"
                  class="text-blue-500 hover:text-blue-600"
                >
                  {{ $t('user.all.adverts') }} >
                </a>
              </div>
            </div>
            <div class="rounded-lg shadow p-3 bg-white mt-5 dark:bg-gray-700">
              <p class="font-bold pb-3 p-2 dark:text-gray-200 text-lg">
                {{ $t('location') }}
              </p>
              <p class="text-gray-600 p-2 dark:text-gray-200">
                {{ $t('address') }}: {{ advert.region?.name }} {{ advert.address }}
              </p>
              <div class="flex items-center justify-center p-2">
                <img
                  src="https://inweb.ua/blog/wp-content/uploads/2020/09/vstavte-etot-kod-na-svoyu-html-stranitsu-ili-vidzhet.jpg"
                  alt=""
                >
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="isMessengerOpen"
        class="fixed bottom-20 right-6 h-[400px] bg-white border border-gray-300 rounded-lg shadow-lg z-50 flex flex-col"
      >
        <div
          class="bg-blue-600 text-white px-4 py-2 rounded-t-lg flex justify-between items-center"
        >
          <span>{{ $t('chat.with.author') }}</span>
          <button
            class="text-white text-xl font-bold leading-none"
            @click="toggleMessenger"
          >
            ×
          </button>
        </div>
        <div class="flex flex-col h-full bg-white border rounded shadow-sm">
          <div
            v-if="messages?.data.length"
            class="flex-1 overflow-y-auto p-4 space-y-2 max-w-[340px]"
          >
            <div
              v-for="message in messages.data.slice().reverse()"
              :key="message.id"
              class="flex items-end"
              :class="message.user.id === user.id ? 'justify-end' : 'justify-start'"
            >
              <template v-if="message.user.id === user.id">
                <div class="flex items-end gap-2 ml-auto">
                  <div class="px-4 py-2 rounded-lg max-w-xs break-words bg-blue-100 text-right">
                    {{ message.message }}
                  </div>
                  <img
                    class="w-10 h-10 rounded-full"
                    :src="getFullPathForAvatarImage(message.user.avatar_url)"
                    alt="Аватар"
                  >
                </div>
              </template>

              <template v-else>
                <div class="flex items-end gap-2 mr-auto">
                  <img
                    class="w-10 h-10 rounded-full"
                    :src="getFullPathForAvatarImage(message.user.avatar_url)"
                    alt="Аватар"
                  >
                  <div class="px-4 py-2 rounded-lg max-w-xs break-words bg-gray-100 text-left">
                    {{ message.message }}
                  </div>
                </div>
              </template>
            </div>
          </div>

          <div
            v-else
            class="flex-1 overflow-y-auto p-4 space-y-2 text-center"
          >
            {{ $t('no.messages.yet') }}
          </div>
          <div class="border-t p-3 flex items-center gap-2 w-full">
            <form @submit.prevent="sendMessage">
              <button
                class="text-gray-500 hover:text-yellow-400 transition"
                :title="$t('emojis')"
                @click="openEmojis"
              >
                😊
              </button>
              <label
                class="cursor-pointer text-gray-500 hover:text-blue-500"
                :title="$t('attach.file')"
              >
                <input
                  type="file"
                  class="hidden"
                  @change="handleFileUpload"
                > 📎
              </label>
              <input
                v-model="messageForm.message"
                type="text"
                :placeholder="$t('write.message')"
                class="flex-1 border rounded-lg px-4 py-2 mr-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                @keyup.enter="sendMessage"
              >
              <button
                type="submit"
                :title="$t('send')"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition"
              >
                ⤊
              </button>
            </form>
          </div>
        </div>
      </div>
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
    </div>
  </AuthenticatedLayout>
</template>
