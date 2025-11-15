```vue
<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Locale from '@/Layouts/Partials/Locale.vue';
import Notifications from '@/Layouts/Partials/Notifications.vue';
import AdminResponsiveNavigationMenu from '@/Layouts/Partials/AdminResponsiveNavigationMenu.vue';
import SideMenu from '@/Layouts/Partials/SideMenu.vue';
import ArrowLeftIcon from '@/Components/Icon/ArrowLeftIcon.vue';
import { computed, ref } from 'vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import ToastRenderer from '@/Components/ToastRenderer.vue';

const showingNavigationDropdown = ref(false);
const flash = computed(() => usePage().props.flash);

const toggleFullscreen = () => {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    document.documentElement.requestFullscreen();
  }
};
</script>

<template>
  <div
    class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col transition-colors duration-300"
  >
    <nav
      class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-colors duration-300"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
          <!-- Left side - Logo and main navigation -->
          <div class="flex items-center">
            <div class="flex shrink-0 items-center">
              <Link
                :href="route('admin.index')"
                class="flex items-center"
                :aria-label="$t('admin_dashboard')"
              >
                <ApplicationLogo
                  class="block h-20 w-auto fill-current text-gray-800 dark:text-gray-200 transition-colors duration-300"
                />
              </Link>
            </div>

            <div class="hidden lg:flex lg:items-center lg:ml-10 lg:space-x-6">
              <NavLink
                :href="route('main')"
                class="dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 transition-colors duration-200 flex items-center text-sm font-medium"
              >
                <ArrowLeftIcon class="w-4 h-4 mr-2" />
                Повернутися на головну
              </NavLink>
            </div>
          </div>

          <!-- Right side - User actions -->
          <div class="hidden sm:flex sm:items-center sm:space-x-4">
            <!-- Fullscreen toggle -->
            <button
              class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
              :aria-label="$t('toggle_fullscreen')"
              @click="toggleFullscreen"
            >
              <svg
                class="w-5 h-5 text-gray-700 dark:text-gray-300"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 4H4m0 0v4m0-4 5 5m7-5h4m0 0v4m0-4-5 5M8 20H4m0 0v-4m0 4 5-5m7 5h4m0 0v-4m0 4-5-5"
                />
              </svg>
            </button>

            <!-- Locale selector -->
            <div class="relative">
              <Locale />
            </div>

            <!-- Dark mode toggle -->
            <div class="relative">
              <DarkModeToggle />
            </div>

            <!-- Notifications -->
            <div class="relative">
              <Notifications />
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="-me-2 flex items-center sm:hidden">
            <button
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200"
              :aria-expanded="showingNavigationDropdown"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
            >
              <span class="sr-only">{{
                showingNavigationDropdown ? $t('close_menu') : $t('open_menu')
              }}</span>
              <svg
                class="h-6 w-6"
                :class="{ hidden: showingNavigationDropdown }"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
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
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
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

      <!-- Mobile navigation menu -->
      <AdminResponsiveNavigationMenu :showing-navigation-dropdown="showingNavigationDropdown" />
    </nav>

    <!-- Page header -->
    <header
      v-if="$slots.header"
      class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-300"
    >
      <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <div class="flex flex-1">
      <!-- Sidebar -->
      <SideMenu />

      <!-- Main content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <ToastRenderer :flash="flash" />
        <slot />
      </main>
    </div>

    <!-- Footer -->
    <footer
      class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300"
      aria-labelledby="footer-heading"
    >
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
          &copy; {{ new Date().getFullYear() }} {{ $t('app_name') }}.
          {{ $t('all_rights_reserved') }}.
        </p>
      </div>
    </footer>
  </div>
</template>
```
