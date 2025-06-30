<script setup lang="ts">
import Dropdown from '@/Components/Dropdown.vue';
import HelperCenterIcon from '@/Components/Icon/HelperCenterIcon.vue';
import CommandLineIcon from '@/Components/Icon/CommandLineIcon.vue';
import AccountIcon from '@/Components/Icon/AccountIcon.vue';
import VerifyIcon from '@/Components/Icon/VerifyIcon.vue';
import SettingsIcon from '@/Components/Icon/SettingsIcon.vue';
import LogoutIcon from '@/Components/Icon/LogoutIcon.vue';
import IntegrationIcon from '@/Components/Icon/IntegrationIcon.vue';
import GuideIcon from '@/Components/Icon/GuideIcon.vue';
import UpDownIcon from '@/Components/Icon/UpDownIcon.vue';
import { Link } from '@inertiajs/vue3';
import ArrowDownIcon from '@/Components/Icon/ArrowDownIcon.vue';
import { getFullPathForAvatarImage } from '@/helpers.js';
</script>

<template>
  <Dropdown
    align="right"
    width="96"
  >
    <template #trigger>
      <span class="inline-flex rounded-md">
        <button
          type="button"
          class="dark:bg-gray-700 dark:text-white hover:bg-gray-300 inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2.5 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
        >
          {{ $page.props.auth?.user?.name ?? $t('Your Profile') }}

          <ArrowDownIcon />
        </button>
      </span>
    </template>

    <template #content>
      <div class="flex items-center justify-center relative">
        <div
          class="w-full max-w-sm rounded-lg bg-white p-3 divide-y divide-gray-200 dark:bg-gray-700"
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
                <div class="font-medium relative text-xl leading-tight text-gray-900">
                  <span class="flex">
                    <span class="truncate relative pr-8 dark:text-gray-200">
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
          <div
            aria-label="navigation"
            class="py-2"
          >
            <nav class="grid gap-1">
              <a
                v-if="$page.props.auth?.user"
                v-can="'admin'"
                :href="route('admin.index')"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <CommandLineIcon /> <span>{{ $t('Admin Panel') }}</span>
              </a>
              <a
                :href="route('account.adverts.index')"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <GuideIcon /> <span> {{ $t('Adverts') }}</span>
              </a>
              <a
                :href="route('account.chats.index')"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <IntegrationIcon /> <span> {{ $t('chat.title') }}</span>
              </a>
              <a
                :href="route('account.profile.index')"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <AccountIcon /> <span>{{ $t('Profile') }}</span>
              </a>
              <a
                :href="route('account.profile.settings')"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <SettingsIcon /> <span>{{ $t('Settings') }}</span>
              </a>
              <a
                href="/"
                class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md dark:text-gray-200"
              >
                <HelperCenterIcon /> <span>{{ $t('Helper Center') }}</span>
              </a>
            </nav>
          </div>
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
            >
              <LogoutIcon /> <span>{{ $t('login') }}</span>
            </Link>
          </div>
        </div>
      </div>
    </template>
  </Dropdown>
</template>

<style scoped></style>
