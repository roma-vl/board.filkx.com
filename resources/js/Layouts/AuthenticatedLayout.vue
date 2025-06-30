<script setup>
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Locale from '@/Layouts/Partials/Locale.vue';
import RattingPopup from '@/Components/RattingPopup.vue';
import FacebookFooterIcon from '@/Components/Icon/FacebookFooterIcon.vue';
import GithubFooterIcon from '@/Components/Icon/GithubFooterIcon.vue';
import YoutubeFooterIcon from '@/Components/Icon/YoutubeFooterIcon.vue';
import SupportChat from '@/Components/SupportChat.vue';
import ProfileMenuHeader from '@/Components/ProfileMenuHeader.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import Notifications from '@/Layouts/Partials/Notifications.vue';
import ToastRenderer from '@/Components/ToastRenderer.vue';

const showingNavigationDropdown = ref(false);
const flash = computed(() => usePage().props.flash);
const showRatingPopup = ref(false);
const handleSubmit = (message) => {
  showRatingPopup.value = false;
};
// onMounted(() => {
//     setTimeout(() => {
//         showRatingPopup.value = true;
//     }, 300000);
// });
</script>

<template>
  <div
    class="flex flex-col min-h-screen bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-100 transition-colors duration-300"
  >
    <!-- Navigation Bar -->
    <nav
      class="border-b border-neutral-200 bg-white/80 dark:bg-gray-900 dark:border-gray-700 backdrop-blur-md"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 justify-between items-center">
          <Link :href="route('main')">
            <ApplicationLogo
              class="block w-28 fill-current text-primary-600 dark:text-primary-400"
            />
          </Link>

          <!-- Desktop Navigation -->
          <div class="hidden sm:flex sm:items-center space-x-4">
            <a
              v-if="!route().current('account.adverts.create')"
              :href="route('account.adverts.create')"
              class="relative inline-flex items-center justify-center px-6 py-3 font-semibold text-white bg-gradient-to-br from-indigo-600 to-fuchsia-600 rounded-xl shadow-xl hover:shadow-2xl transition group"
            >
              <span
                class="absolute inset-0 bg-white opacity-10 group-hover:opacity-20 rounded-xl"
              />
              {{ $t('Add Advert') }}
            </a>

            <Locale />
            <Notifications />
            <DarkModeToggle />
            <ProfileMenuHeader />
          </div>

          <!-- Mobile Toggle -->
          <div class="sm:hidden">
            <button
              class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:bg-neutral-100 dark:hover:bg-neutral-800"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
            >
              <svg
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div
        v-if="showingNavigationDropdown"
        class="sm:hidden px-4 pb-4 border-t dark:border-neutral-800"
      >
        <div class="text-base font-medium">
          {{ $page.props.auth?.user?.name ?? 'Ваш профіль' }}
        </div>
        <div class="text-sm text-neutral-500 dark:text-neutral-400">
          {{ $page.props.auth?.user?.email ?? '' }}
        </div>

        <div class="mt-4 space-y-2">
          <ResponsiveNavLink :href="route('account.profile.settings')">
            Profile
          </ResponsiveNavLink>
          <ResponsiveNavLink
            :href="route('logout')"
            method="post"
            as="button"
          >
            Log Out
          </ResponsiveNavLink>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    <header
      v-if="$slots.header"
      class="bg-white dark:bg-neutral-900 shadow"
    >
      <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main class="flex-grow bg-gray-100 dark:bg-gray-800 p-4">
      <ToastRenderer :flash="flash" />
      <slot />
    </main>

    <!-- Footer -->
    <footer
      class="mt-12 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300"
    >
      <SupportChat />
      <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div>
            <ApplicationLogo class="w-28 fill-current mb-4" />
            <p class="mb-4">
              Зозулька — твій надійний майданчик для оголошень. Все для людей.
            </p>
            <div class="flex space-x-4">
              <FacebookFooterIcon class="w-8 h-8 hover:text-blue-600 transition" />
              <GithubFooterIcon
                class="w-8 h-8 hover:text-gray-900 dark:hover:text-white transition"
              />
              <YoutubeFooterIcon class="w-8 h-8 hover:text-red-600 transition" />
            </div>
          </div>

          <div>
            <h4
              class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Навігація
            </h4>
            <ul class="mt-4 space-y-2">
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Допомога та Зворотній зв'язок</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Блог Snail</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Контакти</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >FAQ</a>
              </li>
            </ul>
          </div>

          <div>
            <h4
              class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Навігація
            </h4>
            <ul class="mt-4 space-y-2">
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Карта сайту</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Про нас</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Умови користування</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >Політика конфіденційності</a>
              </li>
            </ul>
          </div>
        </div>
        <div
          class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500 dark:text-gray-400"
        >
          &copy; {{ new Date().getFullYear() }}
          <a
            href="https://filkx.com"
            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition"
          >filkx.com</a>
        </div>
      </div>
    </footer>

    <RattingPopup
      v-model:visible="showRatingPopup"
      @submit="handleSubmit"
    />
  </div>
</template>
