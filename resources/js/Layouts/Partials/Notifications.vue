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
  try {
    const res = await axios.get(fullPath() + '/notifications');
    notifications.value = res.data.notifications;
    unreadCount.value = res.data.unread_count;
  } catch (error) {
    console.error('Failed to load notifications:', error);
  }
});

const markAllRead = async () => {
  try {
    await axios.post(fullPath() + '/notifications/mark-all-read');
    notifications.value.forEach((n) => (n.read_at = new Date().toISOString()));
    unreadCount.value = 0;
  } catch (error) {
    console.error('Failed to mark notifications as read:', error);
  }
};
</script>

<template>
  <Dropdown
    align="right"
    width="96"
    :dropdown-classes="[
      'bg-white dark:bg-gray-800 shadow-lg rounded-md ring-1 ring-black ring-opacity-5 dark:ring-gray-700',
    ]"
  >
    <template #trigger>
      <button
        type="button"
        class="relative rounded-md p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors"
        :aria-label="
          unreadCount > 0
            ? $t('Notifications ({count})', { count: unreadCount })
            : $t('Notifications')
        "
        @click="toggleDropdown"
      >
        <NotificationIcon class="h-6 w-6" />
        <span
          v-if="unreadCount > 0"
          class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white"
          aria-label="Unread notifications"
        >
          {{ unreadCount }}
        </span>
      </button>
    </template>

    <template #content>
      <div class="w-96 max-h-96 overflow-hidden">
        <div
          class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center justify-between"
        >
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
            {{ $t('Notifications') }}
          </h3>
          <button
            v-if="unreadCount > 0"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
            @click="markAllRead"
          >
            {{ $t('Mark all as read') }}
          </button>
        </div>

        <ul
          v-if="notifications.length"
          class="max-h-80 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700"
        >
          <li
            v-for="notification in notifications"
            :key="notification.id"
            :class="[
              'px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors cursor-pointer',
              !notification.read_at ? 'bg-blue-50 dark:bg-gray-750' : 'bg-white dark:bg-gray-800',
            ]"
          >
            <div class="flex items-start space-x-3">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white break-words">
                  {{ notification.data.text || $t('New notification') }}
                </p>
                <div class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                  <TimerIcon class="h-4 w-4 mr-1" />
                  <time :datetime="notification.created_at">
                    {{ notification.created_at }}
                  </time>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <div
          v-else
          class="px-4 py-12 text-center"
        >
          <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $t('No notifications yet') }}
          </p>
        </div>
      </div>
    </template>
  </Dropdown>
</template>
