<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
</script>

<template>
  <Dropdown
    align="right"
    width="48"
    :dropdown-classes="[
      'bg-white dark:bg-gray-800 shadow-lg rounded-md ring-1 ring-black ring-opacity-5 dark:ring-gray-700',
    ]"
  >
    <template #trigger>
      <button
        type="button"
        class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 dark:text-gray-300 transition-colors duration-200"
        :aria-label="$t('Change language')"
      >
        <span class="flex items-center">
          <span class="mr-2">{{ $page.props.locale === 'uk' ? '🇺🇦' : '🇬🇧' }}</span>
          <svg
            class="ml-1 -mr-1 h-4 w-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </span>
      </button>
    </template>

    <template #content>
      <div class="py-1">
        <DropdownLink
          :href="route('greeting', { locale: 'uk' })"
          class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
        >
          <span class="mr-3">🇺🇦</span>
          Українська
        </DropdownLink>

        <DropdownLink
          :href="route('greeting', { locale: 'en' })"
          class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
        >
          <span class="mr-3">🇬🇧</span>
          English
        </DropdownLink>
      </div>
    </template>
  </Dropdown>
</template>
