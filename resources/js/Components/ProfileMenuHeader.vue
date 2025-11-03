<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import ArrowDownIcon from '@/Components/Icon/ArrowDownIcon.vue';
import VerifyIcon from '@/Components/Icon/VerifyIcon.vue';
import CommandLineIcon from '@/Components/Icon/CommandLineIcon.vue';
import AccountIcon from '@/Components/Icon/AccountIcon.vue';
import SettingsIcon from '@/Components/Icon/SettingsIcon.vue';
import LogoutIcon from '@/Components/Icon/LogoutIcon.vue';
import IntegrationIcon from '@/Components/Icon/IntegrationIcon.vue';
import GuideIcon from '@/Components/Icon/GuideIcon.vue';
import HelperCenterIcon from '@/Components/Icon/HelperCenterIcon.vue';
import UpDownIcon from '@/Components/Icon/UpDownIcon.vue';
import { getFullPathForAvatarImage } from '@/helpers.js';

const isOpen = ref(false);
const menu = ref(null);
const button = ref(null);

const toggleOpen = async () => {
  isOpen.value = !isOpen.value;

  if (isOpen.value) {
    await nextTick();
    updatePosition();
  }
};

const closeMenu = (event) => {
  if (
    menu.value &&
    !menu.value.contains(event.target) &&
    button.value &&
    !button.value.contains(event.target)
  ) {
    isOpen.value = false;
  }
};

const menuStyles = ref({
  position: 'absolute',
  top: '0px',
  left: '0px',
  visibility: 'hidden',
  zIndex: '9999',
  minWidth: '320px',
});

const updatePosition = () => {
  if (!button.value || !menu.value) return;

  const btnRect = button.value.getBoundingClientRect();
  const menuWidth = menu.value.offsetWidth || 320;

  menuStyles.value = {
    position: 'absolute',
    top: `${btnRect.bottom + window.scrollY}px`,
    left: `${btnRect.right - menuWidth + window.scrollX}px`,
    visibility: 'visible',
    zIndex: '9999',
    minWidth: '320px',
  };
};

onMounted(() => {
  button.value = document.querySelector('.profile-menu-button');
  window.addEventListener('resize', updatePosition);
  window.addEventListener('scroll', updatePosition);
  document.addEventListener('click', closeMenu);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', updatePosition);
  window.removeEventListener('scroll', updatePosition);
  document.removeEventListener('click', closeMenu);
});

watch(isOpen, async (newVal) => {
  if (newVal) {
    await nextTick();
    updatePosition();
  }
});
</script>

<template>
  <div class="relative inline-block text-left">
    <button
      type="button"
      class="profile-menu-button inline-flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors"
      :aria-expanded="isOpen"
      :aria-label="$t('User menu')"
      @click="toggleOpen"
    >
      <img
        :src="getFullPathForAvatarImage($page.props.auth?.user?.avatar_url)"
        :alt="$page.props.auth?.user?.name"
        class="h-8 w-8 rounded-full object-cover"
        width="32"
        height="32"
      >
      <span class="hidden md:inline truncate max-w-xs">
        {{ $page.props.auth?.user?.name ?? $t('Your Profile') }}
      </span>
      <ArrowDownIcon class="h-4 w-4" />
    </button>

    <teleport to="body">
      <div
        v-if="isOpen"
        ref="menu"
        :style="menuStyles"
        class="bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-700"
        role="menu"
        aria-orientation="vertical"
        tabindex="-1"
      >
        <div class="py-1">
          <!-- User Header -->
          <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
              <img
                :src="getFullPathForAvatarImage($page.props.auth?.user?.avatar_url)"
                :alt="$page.props.auth?.user?.name"
                class="h-10 w-10 rounded-full object-cover"
                width="40"
                height="40"
              >
              <div class="flex-1 min-w-0">
                <div class="flex items-center">
                  <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ $page.props.auth?.user?.name }}
                  </p>
                  <span
                    v-if="$page.props.auth?.user?.is_verified"
                    class="ml-2 text-blue-500"
                    :title="$t('Verified account')"
                  >
                    <VerifyIcon class="h-4 w-4" />
                  </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                  {{ $page.props.auth?.user?.email }}
                </p>
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="py-1">
            <a
              v-if="$page.props.auth?.user"
              v-can="'admin'"
              :href="route('admin.index')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <CommandLineIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Admin Panel') }}
            </a>

            <a
              :href="route('account.adverts.index')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <GuideIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Adverts') }}
            </a>

            <a
              :href="route('account.chats.index')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <IntegrationIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('chat.title') }}
            </a>

            <a
              :href="route('account.profile.index')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <AccountIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Profile') }}
            </a>

            <a
              :href="route('account.profile.settings')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <SettingsIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Settings') }}
            </a>

            <a
              href="/"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <HelperCenterIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Helper Center') }}
            </a>
          </div>

          <!-- Account Balance -->
          <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ $t('Account Balance') }}
                </p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                  0.00 грн
                </p>
              </div>
              <button
                type="button"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 dark:bg-indigo-900/20 dark:text-indigo-300 dark:hover:bg-indigo-900/30 transition-colors"
              >
                {{ $t('Top up') }}
              </button>
            </div>
          </div>

          <!-- Footer -->
          <div class="border-t border-gray-200 dark:border-gray-700 py-1">
            <Link
              v-if="$page.props.auth?.user"
              :href="route('logout')"
              method="post"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <LogoutIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Log Out') }}
            </Link>

            <Link
              v-else
              :href="route('login')"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <LogoutIcon class="mr-3 h-5 w-5 text-gray-400" />
              {{ $t('Log In') }}
            </Link>
          </div>
        </div>
      </div>
    </teleport>
  </div>
</template>
