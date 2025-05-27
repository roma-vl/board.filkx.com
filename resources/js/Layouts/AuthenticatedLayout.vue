<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Locale from '@/Layouts/Partials/Locale.vue';
import RattingPopup from '@/Components/RattingPopup.vue';
import FacebookFooterIcon from '@/Components/Icon/FacebookFooterIcon.vue';
import GithubFooterIcon from '@/Components/Icon/GithubFooterIcon.vue';
import YoutubeFooterIcon from '@/Components/Icon/YoutubeFooterIcon.vue';
import SupportChat from '@/Components/SupportChat.vue';
import ProfileMenuHeader from '@/Components/ProfileMenuHeader.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import Notifications from '@/Layouts/Partials/Notifications.vue';

const showingNavigationDropdown = ref(false);

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
  <div>
    <div class="flex flex-col min-h-screen bg-gray-100">
      <!-- style='background: url("/storage/images/background/1.jpg") center top no-repeat;' -->
      <nav class="border-b border-gray-100 bg-white/80 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-20 justify-between">
            <div class="flex">
              <div class="flex shrink-0 items-center">
                <Link :href="route('main')">
                  <ApplicationLogo class="block fill-current w-24" />
                </Link>
              </div>
            </div>

            <div class="hidden sm:ms-6 sm:flex sm:items-center">
              <div class="relative ms-3">
                <a
                  v-if="!route().current('account.adverts.create')"
                  :href="route('account.adverts.create')"
                  class="text pointer relative inline-flex items-center justify-center px-6 py-3 overflow-hidden font-medium text-white transition-all bg-gradient-to-br from-violet-500 to-fuchsia-500 rounded-xl shadow-lg hover:shadow-xl group"
                >
                  <span
                    class="absolute inset-0 w-full h-full bg-white opacity-10 group-hover:opacity-20"
                  />
                  {{ $t('Add Advert') }}
                </a>
              </div>
              <div class="relative ms-3">
                <Locale />
              </div>
              <div class="relative ms-3">
                <Notifications />
              </div>
              <div class="relative ms-3">
                <DarkModeToggle />
              </div>
              <div class="relative ms-3">
                <ProfileMenuHeader />
              </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
              <button
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                @click="showingNavigationDropdown = !showingNavigationDropdown"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
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

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <div class="border-t border-gray-200 pb-1 pt-4">
            <div class="px-4">
              <div class="text-base font-medium text-gray-800">
                {{ $page.props.auth?.user?.name ?? 'Ваш профіль' }}
              </div>
              <div class="text-sm font-medium text-gray-500">
                {{ $page.props.auth?.user?.email ?? '' }}
              </div>
            </div>

            <div class="mt-3 space-y-1">
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
        </div>
      </nav>

      <!-- Page Heading -->
      <header
        v-if="$slots.header"
        class="bg-white shadow"
      >
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-grow">
        <slot />
      </main>

      <footer
        class="bg-gray-100"
        aria-labelledby="footer-heading"
      >
        <SupportChat />

        <div>
          <div class="mx-auto max-w-7xl px-6 pt-6">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
              <div>
                <p class="text-sm leading-6 p-2 text-gray-600">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque, fugit.
                </p>

                <div class="flex space-x-6 items-center">
                  <Link :href="route('main')">
                    <ApplicationLogo class="block fill-current w-24" />
                  </Link>
                  <a
                    href="#"
                    class="text-gray-400 hover:text-gray-500"
                  >
                    <span class="sr-only">Facebook</span>
                    <FacebookFooterIcon />
                  </a>
                  <a
                    href="#"
                    class="text-gray-400 hover:text-gray-500"
                  >
                    <span class="sr-only">GitHub</span>
                    <GithubFooterIcon />
                  </a>
                  <a
                    href="#"
                    class="text-gray-400 hover:text-gray-500"
                  >
                    <span class="sr-only">YouTube</span>
                    <YoutubeFooterIcon />
                  </a>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                  <div>
                    <h3 class="text-sm font-semibold leading-6 text-gray-900">
                      Solutions
                    </h3>
                    <ul
                      role="list"
                      class="mt-6 space-y-4"
                    >
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Marketing</a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Analytics</a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Commerce</a>
                      </li>
                    </ul>
                  </div>
                  <div class="mt-10 md:mt-0">
                    <h3 class="text-sm font-semibold leading-6 text-gray-900">
                      Support
                    </h3>
                    <ul
                      role="list"
                      class="mt-6 space-y-4"
                    >
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Pricing</a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Documentation</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-8">
                  <div>
                    <h3 class="text-sm font-semibold leading-6 text-gray-900">
                      Company
                    </h3>
                    <ul
                      role="list"
                      class="mt-6 space-y-4"
                    >
                      <li>
                        <a
                          :href="route('contact')"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Contact</a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Blog</a>
                      </li>
                    </ul>
                  </div>
                  <div class="mt-10 md:mt-0">
                    <h3 class="text-sm font-semibold leading-6 text-gray-900">
                      Legal
                    </h3>
                    <ul
                      role="list"
                      class="mt-6 space-y-4"
                    >
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Claim</a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                        >Privacy</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-6 border-t border-gray-900/10 pt-8">
              <p class="text-xs leading-5 text-gray-500">
                &copy; 2025 Зозулька, Inc. All rights reserved.
              </p>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <RattingPopup
    v-model:visible="showRatingPopup"
    @submit="handleSubmit"
  />
</template>
