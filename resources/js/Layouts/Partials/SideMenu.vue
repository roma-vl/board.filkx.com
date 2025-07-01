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
    //banner-container
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
    <button
      type="button"
      class="dark:text-gray-200 dark:hover:bg-gray-900 fixed top-4 left-4 p-2 h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase transition-all hover:bg-white/10 active:bg-white/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
      @click.stop="toggleSidebar"
    >
      <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
        <BurgerIcon />
      </span>
    </button>

    <div
      v-if="isOpen"
      class="menu-container fixed left-0 top-0 flex h-full w-full max-w-[20rem] flex-col rounded-xl z-10 dark:bg-gray-800 bg-white bg-clip-border pt-6 text-gray-700 shadow-xl shadow-blue-gray-900/5"
    >
      <div
        aria-label="header"
        class="flex space-x-4 items-center p-4"
      >
        <div
          aria-label="avatar"
          class="flex mr-auto items-center space-x-4"
        >
          <img
            :src="getFullPathForAvatarImage($page.props.auth.user.avatar_url)"
            :alt="$page.props.auth.user.name"
            class="w-16 h-16 shrink-0 rounded-full"
          >
          <div class="space-y-2 flex flex-col flex-1 truncate">
            <div class="font-medium relative text-xl leading-tight text-gray-900">
              <span class="flex">
                <span class="truncate relative pr-8 dark:text-white">
                  {{ $page.props.auth.user.name }}
                  <span
                    aria-label="verified"
                    class="absolute top-1/2 -translate-y-1/2 right-0 inline-block rounded-full"
                  >
                    <VerifyIcon />
                  </span>
                </span>
              </span>
            </div>
            <p class="font-normal text-base leading-tight text-gray-500 truncate dark:text-white">
              {{ $page.props.auth.user.email }}
            </p>
          </div>
        </div>
        <UpDownIcon />
      </div>
      <hr>

      <nav
        class="flex min-w-[240px] flex-col gap-1 p-2 pt-4 font-sans text-base font-normal text-blue-gray-700"
      >
        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-san s text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('adverts')"
            >
              <div class="grid mr-4 place-items-center">
                <FireIcon />
              </div>
              <p
                class="dark:text-white block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Оголошення
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['adverts']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <div
                  role="button"
                  class="justify-between hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <EyeIcon />
                  </div>
                  <a
                    :href="route('admin.adverts.index')"
                    class="dark:text-white block mr-auto font-sans text-blue-gray-900"
                  >
                    Переглянути Оголошення
                  </a>
                  <span class="ml-4" />
                </div>
                <div
                  role="button"
                  class="justify-between hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                  @click="toggleMenu('actions')"
                >
                  <div class="grid mr-4 place-items-center">
                    <PencilIcon />
                  </div>
                  <p class="dark:text-white block mr-auto font-sans text-blue-gray-900">
                    Дії з оголошенням
                  </p>
                  <span class="ml-4">
                    <ArrowDownIcon />
                  </span>
                </div>
                <div
                  v-if="openMenus['actions']"
                  class="flex min-w-[240px]"
                >
                  <a
                    v-can="'adverts'"
                    :href="route('admin.adverts.actions.moderation')"
                    role="button"
                    class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                  >
                    <div class="grid mr-4 place-items-center pl-2">
                      <ArrowRightIcon />
                    </div>
                    Модерувати
                  </a>
                </div>

                <a
                  v-can="'adverts'"
                  :href="route('admin.adverts.category.index')"
                  role="button"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Категорії
                </a>

                <a
                  v-can="'location'"
                  :href="route('admin.locations.index')"
                  role="button"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Міста та області
                </a>
              </nav>
            </div>
          </div>
        </div>
        <a
          v-can="'user.view'"
          :href="route('admin.adverts.orders.index')"
          role="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <DollarIcon />
          </div>
          Замовлення
        </a>
        <a
          v-can="'user.view'"
          :href="route('admin.users.index')"
          role="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <UsersIcon />
          </div>
          Користувачі
        </a>
        <a
          :href="route('admin.tickets.index')"
          role="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <TicketIcon />
          </div>
          Тікети
        </a>
        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-sans text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('manage')"
            >
              <div class="grid mr-4 place-items-center">
                <Keyicon />
              </div>
              <p
                class="block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Управління доступом
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['manage']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <Link
                  v-can="'role'"
                  :href="route('admin.roles.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Ролі
                </Link>
                <Link
                  v-can="'permission'"
                  :href="route('admin.permissions.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Дозволи
                </Link>
              </nav>
            </div>
          </div>
        </div>
        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-sans text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('price')"
            >
              <div class="grid mr-4 place-items-center">
                <DollarIcon />
              </div>
              <p
                class="block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Управління цінами
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['price']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <Link
                  :href="route('admin.coupons.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Купони
                </Link>
                <Link
                  :href="route('admin.coupons.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Тарифи
                </Link>
              </nav>
            </div>
          </div>
        </div>

        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-sans text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('newsletter')"
            >
              <div class="grid mr-4 place-items-center">
                <EnvelopeOpenIcon />
              </div>
              <p
                class="block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Розсилки
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['newsletter']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <Link
                  v-can="'role'"
                  :href="route('admin.newsletters.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Емейл Листи
                </Link>
              </nav>
            </div>
          </div>
        </div>
        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-sans text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('pages')"
            >
              <div class="grid mr-4 place-items-center">
                <ComputerIcon />
              </div>
              <p
                class="block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Статичні сторінки
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['pages']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <Link
                  v-can="'role'"
                  :href="route('admin.pages.index')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Сторінки
                </Link>
              </nav>
            </div>
          </div>
        </div>

        <div class="relative block w-full">
          <div
            role="button"
            class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900"
          >
            <button
              type="button"
              class="rounded-lg hover:bg-gray-200 flex items-center justify-between w-full p-3 font-sans text-xl antialiased font-semibold leading-snug text-left transition-colors border-b-0 select-none border-b-blue-gray-100 text-blue-gray-700 hover:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
              @click="toggleMenu('logs')"
            >
              <div class="grid mr-4 place-items-center">
                <QueueIcon />
              </div>
              <p
                class="block mr-auto font-sans text-base antialiased font-normal leading-relaxed text-blue-gray-900"
              >
                Логи
              </p>
              <span class="ml-4">
                <ArrowDownIcon />
              </span>
            </button>
          </div>
          <div
            v-if="openMenus['logs']"
            class="overflow-hidden"
          >
            <div
              class="block w-full py-1 font-sans text-sm antialiased font-light leading-normal text-gray-700"
            >
              <nav
                class="flex min-w-[240px] flex-col gap-1 p-0 font-sans text-base font-normal text-blue-gray-700"
              >
                <a
                  :href="route('admin.logs')"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Системні логи
                </a>

                <a
                  :href="route('admin.activity.logs')"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Activity log логи
                </a>
                <a
                  :href="route('admin.logs')"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
                >
                  <div class="grid mr-4 place-items-center">
                    <ArrowRightIcon />
                  </div>
                  Сервіс логи
                </a>
              </nav>
            </div>
          </div>
        </div>
        <hr class="my-2 border-blue-gray-50">
        <div
          role="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <InboxIcon />
          </div>
          Inbox
          <div class="grid ml-auto place-items-center justify-self-end">
            <div
              class="relative grid items-center px-2 py-1 font-sans text-xs font-bold uppercase rounded-full select-none whitespace-nowrap bg-blue-gray-500/20 text-blue-gray-900"
            >
              <span class="">14</span>
            </div>
          </div>
        </div>
        <Link
          :href="route('account.profile.settings')"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <UserIcon />
          </div>
          Налаштування профілю
        </Link>
        <a
          :href="route('admin.settings.index')"
          role="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <SettingsIcon />
          </div>
          Налаштування сайту
        </a>
        <Link
          :href="route('logout')"
          method="post"
          type="button"
          class="hover:bg-gray-200 flex items-center w-full p-3 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900 dark:text-gray-200 dark:hover:bg-gray-900"
        >
          <div class="grid mr-4 place-items-center">
            <LogoutIcon />
          </div>
          Log Out
        </Link>
      </nav>
      <div
        v-if="openBanner"
        role="alert"
        class="banner-container relative block w-full px-4 py-4 mt-auto text-base text-white bg-gray-900 rounded-lg font-regular"
      >
        <div class="mr-12">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            aria-hidden="true"
            class="w-12 h-12 mb-4"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"
            />
          </svg>
          <h6
            class="block mb-1 font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-inherit"
          >
            Upgrade to PRO
          </h6>
          <p
            class="block font-sans text-sm antialiased font-normal leading-normal text-inherit opacity-80"
          >
            Upgrade to Material Tailwind PRO and get even more components, plugins, advanced
            features and premium.
          </p>
        </div>
        <button
          class="!absolute top-3 right-3 h-8 max-h-[32px] w-8 max-w-[32px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-white transition-all hover:bg-white/10 active:bg-white/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
          type="button"
          @click.prevent="toggleBanner"
        >
          <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="w-6 h-6"
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
</template>

<style scoped></style>
