```vue
<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import VerifyIcon from '@/Components/Icon/VerifyIcon.vue';
import UpDownIcon from '@/Components/Icon/UpDownIcon.vue';
import { Link } from '@inertiajs/vue3';
import BurgerIcon from '@/Components/Icon/BurgerIcon.vue';
import ArrowDownIcon from '@/Components/Icon/ArrowDownIcon.vue';
import SettingsIcon from '@/Components/Icon/SettingsIcon.vue';
import UserIcon from '@/Components/Icon/UserIcon.vue';
import TicketIcon from '@/Components/Icon/TicketIcon.vue';
import DollarIcon from '@/Components/Icon/DollarIcon.vue';
import Keyicon from '@/Components/Icon/Keyicon.vue';
import EnvelopeOpenIcon from '@/Components/Icon/EnvelopeOpenIcon.vue';
import ComputerIcon from '@/Components/Icon/ComputerIcon.vue';
import QueueIcon from '@/Components/Icon/QueueIcon.vue';
import UsersIcon from '@/Components/Icon/UsersIcon.vue';
import FireIcon from '@/Components/Icon/FireIcon.vue';
import InboxIcon from '@/Components/Icon/InboxIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import ArrowRightIcon from '@/Components/Icon/ArrowRightIcon.vue';
import LogoutIcon from '@/Components/Icon/LogoutIcon.vue';
import { getFullPathForAvatarImage } from '@/helpers.js';
import EyeIcon from '@/Components/Icon/EyeIcon.vue';

const isOpen = ref(false);
const openMenus = ref({});
const openBanner = ref(true);

const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};

const toggleBanner = () => {
  openBanner.value = !openBanner.value;
};

const toggleMenu = (menu) => {
  openMenus.value[menu] = !openMenus.value[menu];
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.menu-container') && !event.target.closest('.banner-container')) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div>
    <!-- Mobile toggle button -->
    <button
      type="button"
      class="dark:text-gray-200 dark:hover:bg-gray-700 fixed top-4 left-4 p-2 h-10 w-10 select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase transition-all hover:bg-gray-100 active:bg-gray-200 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 z-20"
      :aria-label="isOpen ? $t('close_menu') : $t('open_menu')"
      @click.stop="toggleSidebar"
    >
      <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
        <BurgerIcon class="w-5 h-5" />
      </span>
    </button>

    <!-- Sidebar -->
    <div
      v-if="isOpen"
      class="menu-container fixed left-0 top-0 flex h-full w-full max-w-xs flex-col rounded-r-xl z-10 dark:bg-gray-800 bg-white bg-clip-border pt-6 text-gray-700 shadow-xl shadow-gray-900/20 dark:shadow-gray-900/40 transition-all duration-300"
    >
      <!-- User profile header -->
      <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex mr-auto items-center space-x-4">
          <img
            :src="getFullPathForAvatarImage($page.props.auth.user.avatar_url)"
            :alt="$page.props.auth.user.name"
            class="w-16 h-16 shrink-0 rounded-full object-cover"
            width="64"
            height="64"
          >
          <div class="space-y-1 flex flex-col flex-1 truncate">
            <div class="font-medium text-lg leading-tight text-gray-900 dark:text-white">
              <span class="flex items-center">
                <span class="truncate pr-6">
                  {{ $page.props.auth.user.name }}
                </span>
                <span class="absolute right-4 inline-block rounded-full">
                  <VerifyIcon class="w-4 h-4 text-blue-500" />
                </span>
              </span>
            </div>
            <p class="font-normal text-sm leading-tight text-gray-500 dark:text-gray-400 truncate">
              {{ $page.props.auth.user.email }}
            </p>
          </div>
        </div>
        <UpDownIcon class="w-5 h-5 text-gray-400 dark:text-gray-500" />
      </div>

      <!-- Navigation -->
      <nav
        class="flex-1 flex flex-col gap-1 p-2 pt-4 font-sans text-base font-normal text-gray-700 dark:text-gray-300 overflow-y-auto max-h-[calc(100vh-200px)]"
      >
        <!-- Adverts menu -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('adverts')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <FireIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Оголошення </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['adverts'] }"
            />
          </button>

          <div
            v-if="openMenus['adverts']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <Link
                :href="route('admin.adverts.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <EyeIcon class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0" />
                <span class="text-sm text-gray-700 dark:text-gray-300">
                  Переглянути Оголошення
                </span>
              </Link>

              <button
                type="button"
                class="w-full p-2.5 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
                @click="toggleMenu('actions')"
              >
                <div class="flex items-center">
                  <PencilIcon class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0" />
                  <span class="text-sm text-gray-700 dark:text-gray-300"> Дії з оголошенням </span>
                </div>
                <ArrowDownIcon
                  class="w-3 h-3 text-gray-400 dark:text-gray-500 transition-transform duration-200"
                  :class="{ 'rotate-180': openMenus['actions'] }"
                />
              </button>

              <div
                v-if="openMenus['actions']"
                class="overflow-hidden transition-all duration-200 pl-6"
              >
                <Link
                  v-can="'adverts'"
                  :href="route('admin.adverts.actions.moderation')"
                  class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center ml-4"
                >
                  <ArrowRightIcon
                    class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                  />
                  <span class="text-sm text-gray-700 dark:text-gray-300"> Модерувати </span>
                </Link>
              </div>

              <Link
                v-can="'adverts'"
                :href="route('admin.adverts.category.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Категорії </span>
              </Link>

              <Link
                v-can="'location'"
                :href="route('admin.locations.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Міста та області </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Orders -->
        <Link
          v-can="'user.view'"
          :href="route('admin.adverts.orders.index')"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <DollarIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Замовлення </span>
        </Link>

        <!-- Users -->
        <Link
          v-can="'user.view'"
          :href="route('admin.users.index')"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <UsersIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Користувачі </span>
        </Link>

        <!-- Tickets -->
        <Link
          :href="route('admin.tickets.index')"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <TicketIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Тікети </span>
        </Link>

        <!-- Access management -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('manage')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <Keyicon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Управління доступом </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['manage'] }"
            />
          </button>

          <div
            v-if="openMenus['manage']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <Link
                v-can="'role'"
                :href="route('admin.roles.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Ролі </span>
              </Link>
              <Link
                v-can="'permission'"
                :href="route('admin.permissions.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Дозволи </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Price management -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('price')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <DollarIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Управління цінами </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['price'] }"
            />
          </button>

          <div
            v-if="openMenus['price']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <Link
                :href="route('admin.coupons.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Купони </span>
              </Link>
              <Link
                :href="route('admin.coupons.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Тарифи </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Newsletter -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('newsletter')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <EnvelopeOpenIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Розсилки </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['newsletter'] }"
            />
          </button>

          <div
            v-if="openMenus['newsletter']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <Link
                v-can="'role'"
                :href="route('admin.newsletters.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Емейл Листи </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Static pages -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('pages')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <ComputerIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Статичні сторінки </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['pages'] }"
            />
          </button>

          <div
            v-if="openMenus['pages']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <Link
                v-can="'role'"
                :href="route('admin.pages.index')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Сторінки </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Logs -->
        <div class="relative block w-full">
          <button
            type="button"
            class="w-full p-3 flex items-center justify-between rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 text-left"
            @click="toggleMenu('logs')"
          >
            <div class="flex items-center">
              <div class="mr-4 flex-shrink-0">
                <QueueIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
              </div>
              <span class="font-medium text-gray-900 dark:text-white"> Логи </span>
            </div>
            <ArrowDownIcon
              class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
              :class="{ 'rotate-180': openMenus['logs'] }"
            />
          </button>

          <div
            v-if="openMenus['logs']"
            class="overflow-hidden transition-all duration-200"
          >
            <div class="pl-8 pr-4 space-y-1 mt-1">
              <a
                :href="route('admin.logs')"
                target="_blank"
                rel="noopener noreferrer"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Системні логи </span>
              </a>
              <a
                :href="route('admin.activity.logs')"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Activity log логи </span>
              </a>
              <a
                :href="route('admin.logs')"
                target="_blank"
                rel="noopener noreferrer"
                class="block w-full p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center"
              >
                <ArrowRightIcon
                  class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400 flex-shrink-0"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300"> Сервіс логи </span>
              </a>
            </div>
          </div>
        </div>

        <hr class="my-3 border-gray-200 dark:border-gray-700">

        <!-- Inbox -->
        <div
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <InboxIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white mr-auto"> Inbox </span>
          <div class="flex-shrink-0">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400"
            >
              14
            </span>
          </div>
        </div>

        <!-- Profile settings -->
        <Link
          :href="route('cabinet.profile.settings')"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <UserIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Налаштування профілю </span>
        </Link>

        <!-- Site settings -->
        <Link
          :href="route('admin.settings.index')"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <SettingsIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Налаштування сайту </span>
        </Link>

        <!-- Logout -->
        <Link
          :href="route('logout')"
          method="post"
          type="button"
          class="block w-full p-3 flex items-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <div class="mr-4 flex-shrink-0">
            <LogoutIcon class="w-5 h-5 text-gray-600 dark:text-gray-400" />
          </div>
          <span class="font-medium text-gray-900 dark:text-white"> Log Out </span>
        </Link>
      </nav>

      <!-- Banner -->
      <div
        v-if="openBanner"
        class="banner-container px-4 py-4 bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-lg m-4 mt-auto"
      >
        <div class="flex items-start">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-10 h-10 mr-3 flex-shrink-0"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"
            />
          </svg>
          <div class="flex-1">
            <h6 class="text-base font-semibold mb-1">
              Upgrade to PRO
            </h6>
            <p class="text-sm opacity-90 mb-3">
              Upgrade to Material Tailwind PRO and get even more components, plugins, advanced
              features and premium.
            </p>
          </div>
          <button
            class="!absolute top-3 right-3 h-8 w-8 select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-white transition-all hover:bg-white/10 active:bg-white/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
            type="button"
            @click.prevent="toggleBanner"
          >
            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="w-5 h-5"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Backdrop -->
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-5 transition-opacity duration-300"
      @click="toggleSidebar"
    />
  </div>
</template>

<style scoped>
.menu-container {
  transform: translateX(-100%);
  animation: slideIn 0.3s ease-out forwards;
}

@keyframes slideIn {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
}
</style>
```
