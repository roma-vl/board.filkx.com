<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import NotificationIcon from '@/Components/Icon/NotificationIcon.vue';

import { ref, onMounted } from 'vue';
import axios from 'axios';
import { fullPath } from '@/helpers.js';
import TimerIcon from '@/Components/Icon/TimerIcon.vue';

const show = ref(false);
const notifications = ref([]);
const unreadCount = ref(0);

const toggleDropdown = () => (show.value = !show.value);

onMounted(async () => {
  const res = await axios.get(fullPath() + '/notifications');
  notifications.value = res.data.notifications;
  unreadCount.value = res.data.unread_count;
});

const markAllRead = async () => {
  await axios.post(fullPath() + '/notifications/mark-all-read');
  notifications.value.forEach((n) => (n.read_at = new Date().toISOString()));
  unreadCount.value = 0;
};
</script>

<template>
  <Dropdown
    align="right"
    width="72"
  >
    <template #trigger>
      <div class="flex flex-col items-center">
        <div class="relative flex justify-center">
          <div>
            <button
              class="inline-grid place-items-center align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[32px] min-h-[32px] rounded-md hover:shadow-sm text-stone-800 hover:bg-stone-200 px-4 py-3 dark:bg-gray-700 dark:text-white"
              @click="toggleDropdown"
            >
              <NotificationIcon />
            </button>
          </div>
          <span
            v-if="unreadCount > 0"
            class="absolute -top-2 -right-2 text-xs leading-none rounded-full bg-red-500 text-white px-2 py-0.5 shadow"
          >
            {{ unreadCount }}
          </span>
        </div>
      </div>
    </template>

    <template #content>
      <ul
        v-if="notifications.length"
        class="max-h-96 overflow-y-auto bg-white dark:bg-gray-800 p-2 divide-y divide-gray-200 dark:divide-gray-600 rounded-lg w-full"
      >
        <li
          v-for="n in notifications"
          :key="n.id"
          :class="[
            'px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-all cursor-pointer',
            !n.read_at ? 'bg-violet-50 dark:bg-gray-700' : '',
          ]"
        >
          <div class="flex items-start space-x-3">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-800 dark:text-white">
                {{ n.data.text || 'Нове повідомлення' }}
              </p>
              <div class="flex items-center gap-1 mt-1 text-xs text-gray-500 dark:text-gray-400">
                <TimerIcon class="w-4 h-4" />
                {{ n.created_at }}
              </div>
            </div>
          </div>
        </li>
      </ul>
      <div class="border-t dark:border-gray-700">
        <button
          class="text-sm text-violet-600 hover:text-violet-800 hover:bg-violet-100 font-medium transition max-h-96 overflow-y-auto bg-white dark:bg-gray-800 p-2 dark:divide-gray-600 w-full"
          @click="markAllRead"
        >
          Позначити всі як прочитані
        </button>
      </div>
    </template>
  </Dropdown>
</template>

<style scoped></style>
