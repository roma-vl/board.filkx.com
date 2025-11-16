<script setup>
import { computed, onMounted, ref } from 'vue';
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

const handleSubmit = () => {
  showRatingPopup.value = false;
};

onMounted(() => {
  setTimeout(() => {
    showRatingPopup.value = true;
  }, 300000);
});
</script>

<template>
  <div
    class="min-h-screen flex flex-col bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300 overflow-x-hidden "
  >
    <nav
      class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 backdrop-blur-md shadow-sm"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <Link
            :href="route('main')"
            class="flex-shrink-0"
            :aria-label="$t('Home')"
          >
            <ApplicationLogo
              class="h-20 w-auto fill-current text-indigo-600 dark:text-indigo-400 transition-colors"
            />
          </Link>
          <div class="hidden sm:flex items-center space-x-2 lg:space-x-4">
            <Link
              v-if="!route().current('cabinet.adverts.create')"
              :href="route('cabinet.adverts.create')"
              class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-white dark:focus:ring-offset-gray-900 dark:from-indigo-500 dark:to-purple-500 transition-all duration-200 transform hover:-translate-y-0.5"
              :aria-label="$t('Create Advert')"
            >
              <svg
                class="-ml-1 mr-2 h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              {{ $t('Add Advert') }}
            </Link>

            <div class="flex items-center space-x-2">
              <Locale />
              <Notifications />
              <DarkModeToggle />
              <ProfileMenuHeader />
            </div>
          </div>

          <div class="sm:hidden flex items-center">
            <button
              type="button"
              class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
              :aria-expanded="showingNavigationDropdown"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                class="h-6 w-6"
                :class="{ hidden: showingNavigationDropdown }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
              <svg
                class="h-6 w-6"
                :class="{ hidden: !showingNavigationDropdown }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
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

      <div
        v-show="showingNavigationDropdown"
        class="sm:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900"
      >
        <div class="px-4 pt-4 pb-4 space-y-4">
          <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <div class="text-base font-medium text-gray-800 dark:text-gray-200">
              {{ $page.props.auth?.user?.name ?? 'Guest' }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
              {{ $page.props.auth?.user?.email ?? 'No email' }}
            </div>
          </div>

          <div class="space-y-2">
            <Link
              v-if="!route().current('cabinet.adverts.create')"
              :href="route('cabinet.adverts.create')"
              class="block w-full text-left px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow hover:shadow-md transition-shadow"
            >
              <svg
                class="-ml-1 mr-2 h-4 w-4 inline"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              {{ $t('Add Advert') }}
            </Link>

            <ResponsiveNavLink :href="route('cabinet.profile.settings')">
              {{ $t('Profile Settings') }}
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('logout')"
              method="post"
              as="button"
            >
              {{ $t('Log Out') }}
            </ResponsiveNavLink>
          </div>

          <div
            class="flex items-center space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700"
          >
            <Locale />
            <Notifications />
            <DarkModeToggle />
          </div>
        </div>
      </div>
    </nav>

    <header
      v-if="$slots.header"
      class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
    >
      <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <main class="flex-grow bg-gray-50 dark:bg-gray-800">
      <ToastRenderer :flash="flash" />
      <slot />
    </main>

    <footer
      class="mt-16 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300"
    >
      <SupportChat />
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
          <div class="lg:col-span-1">
            <Link
              :href="route('main')"
              class="block"
              :aria-label="$t('Home')"
            >
              <ApplicationLogo
                class="h-20 w-auto fill-current text-indigo-600 dark:text-indigo-400 pt-0"
              />
            </Link>
            <p class="text-sm mb-4 ml-4 leading-relaxed">
              {{ $t('Filkx — your reliable platform for announcements. Everything for people.') }}
            </p>
            <div class="flex space-x-4 ml-4">
              <a
                href="#"
                class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200"
                :aria-label="$t('Follow us on Facebook')"
              >
                <FacebookFooterIcon class="h-6 w-6" />
              </a>
              <a
                href="#"
                class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
                :aria-label="$t('Follow us on GitHub')"
              >
                <GithubFooterIcon class="h-6 w-6" />
              </a>
              <a
                href="#"
                class="text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                :aria-label="$t('Subscribe on YouTube')"
              >
                <YoutubeFooterIcon class="h-6 w-6" />
              </a>
            </div>
          </div>

          <div>
            <h3
              class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4"
            >
              {{ $t('Navigation') }}
            </h3>
            <ul class="space-y-3">
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Help & Feedback') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Filkx Blog') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Contacts') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('FAQ') }}</a>
              </li>
            </ul>
          </div>

          <div>
            <h3
              class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4"
            >
              {{ $t('Legal') }}
            </h3>
            <ul class="space-y-3">
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Sitemap') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('About Us') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Terms of Service') }}</a>
              </li>
              <li>
                <a
                  href="#"
                  class="text-sm text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-200"
                >{{ $t('Privacy Policy') }}</a>
              </li>
            </ul>
          </div>

          <div>
            <h3
              class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4"
            >
              {{ $t('Contact') }}
            </h3>
            <ul class="space-y-3">
              <li class="flex items-start">
                <svg
                  class="h-5 w-5 text-gray-500 mr-3 mt-0.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-400">admin@filkx.com</span>
              </li>
              <li class="flex items-start">
                <svg
                  class="h-5 w-5 text-gray-500 mr-3 mt-0.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                  />
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-400">+380 (XX) XXX-XX-XX</span>
              </li>
            </ul>
          </div>
        </div>

        <div
          class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center"
        >
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
            &copy; {{ new Date().getFullYear() }}
            <a
              href="https://filkx.com"
              target="_blank"
              rel="noopener noreferrer"
              class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
            >filkx.com</a>
            . {{ $t('All rights reserved.') }}
          </p>
          <div class="flex space-x-6">
            <a
              href="#"
              class="text-sm text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
            >{{ $t('Terms') }}</a>
            <a
              href="#"
              class="text-sm text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
            >{{ $t('Privacy') }}</a>
            <a
              href="#"
              class="text-sm text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
            >{{ $t('Cookies') }}</a>
          </div>
        </div>
      </div>
    </footer>

    <RattingPopup
      v-model:visible="showRatingPopup"
      @submit="handleSubmit"
    />
  </div>
</template>
