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
  minWidth: '240px',
});

const updatePosition = () => {
  if (!button.value || !menu.value) return;

  const btnRect = button.value.getBoundingClientRect();
  const menuWidth = menu.value.offsetWidth || 240;

  menuStyles.value = {
    position: 'absolute',
    top: `${btnRect.bottom + window.scrollY}px`,
    left: `${btnRect.right - menuWidth + window.scrollX}px`,
    visibility: 'visible',
    zIndex: '9999',
    minWidth: '240px',
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
    <!-- Кнопка для відкриття меню -->
    <button
      type="button"
      class="profile-menu-button dark:bg-gray-700 dark:text-white hover:bg-gray-300 inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2.5 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
      aria-haspopup="true"
      aria-expanded="isOpen"
      @click="toggleOpen"
    >
      {{ $page.props.auth?.user?.name ?? $t('Your Profile') }}
      <ArrowDownIcon class="ml-2" />
    </button>

    <teleport to="body">
      <div
        v-if="isOpen"
        ref="menu"
        :style="menuStyles"
        class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-3 divide-y divide-gray-200 dark:divide-gray-600"
        role="menu"
        aria-orientation="vertical"
        tabindex="-1"
      >
        <div
          aria-label="header"
          class="flex space-x-4 items-center p-4"
        >
          <div
            aria-label="avatar"
            class="flex mr-auto items-center space-x-4 relative"
          >
            <img
              :src="getFullPathForAvatarImage($page.props.auth?.user?.avatar_url)"
              :alt="$page.props.auth?.user?.name"
              class="w-16 h-16 shrink-0 rounded-full"
            >
            <div class="space-y-2 flex flex-col flex-1 truncate">
              <div
                class="font-medium relative text-xl leading-tight text-gray-900 dark:text-gray-200"
              >
                <span class="flex">
                  <span class="truncate relative pr-8">
                    {{ $page.props.auth?.user?.name }}
                    <span
                      aria-label="verified"
                      class="absolute top-1/2 -translate-y-1/2 right-0 inline-block rounded-full"
                    >
                      <VerifyIcon />
                    </span>
                  </span>
                </span>
              </div>
              <p
                class="font-normal text-base leading-tight text-gray-500 truncate dark:text-gray-200"
              >
                {{ $page.props.auth?.user?.email }}
              </p>
            </div>
          </div>
          <UpDownIcon />
        </div>

        <nav
          class="py-2 grid gap-1"
          aria-label="navigation"
        >
          <a
            v-if="$page.props.auth?.user && $page.props.auth?.user.is_admin"
            :href="route('admin.index')"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <CommandLineIcon /> <span>{{ $t('Admin Panel') }}</span>
          </a>
          <a
            :href="route('account.adverts.index')"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <GuideIcon /> <span> {{ $t('Adverts') }}</span>
          </a>
          <a
            :href="route('account.chats.index')"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <IntegrationIcon /> <span> {{ $t('chat.title') }}</span>
          </a>
          <a
            :href="route('account.profile.index')"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <AccountIcon /> <span>{{ $t('Profile') }}</span>
          </a>
          <a
            :href="route('account.profile.settings')"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <SettingsIcon /> <span>{{ $t('Settings') }}</span>
          </a>
          <a
            href="/"
            class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <HelperCenterIcon /> <span>{{ $t('Helper Center') }}</span>
          </a>
        </nav>

        <!-- Баланс і кнопка -->
        <div
          aria-label="account-upgrade"
          class="px-4 py-6"
        >
          <div class="flex items-center space-x-1">
            <div class="mr-auto w-2/3">
              <p class="font-medium text-lg text-gray-500 leading-none p-1">
                Ваш рахунок:
              </p>
              <p class="font-normal text-xl text-gray-900 leading-none p-1">
                0 грн.
              </p>
            </div>
            <button
              type="button"
              class="inline-flex px-2 leading-6 py-2 rounded-md bg-indigo-50 hover:bg-indigo-50/80 transition-colors duration-200 text-indigo-500 font-medium text-lg"
            >
              Поповнити гаманець
            </button>
          </div>
        </div>

        <div
          v-if="$page.props.auth?.user"
          aria-label="footer"
          class="pt-2"
        >
          <Link
            :href="route('logout')"
            method="post"
            type="button"
            class="flex items-center space-x-3 py-3 px-4 w-full leading-6 text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
            role="menuitem"
          >
            <LogoutIcon /> <span> {{ $t('logout') }}</span>
          </Link>
        </div>
        <div
          v-else
          aria-label="footer"
          class="pt-2"
        >
          <Link
            :href="route('login')"
            type="button"
            class="flex items-center space-x-3 py-3 px-4 w-full leading-6 text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-200"
            role="menuitem"
          >
            <LogoutIcon /> <span>{{ $t('login') }}</span>
          </Link>
        </div>
      </div>
    </teleport>
  </div>
</template>

<style scoped>
/* За потреби додай кастомні стилі тут */
</style>
