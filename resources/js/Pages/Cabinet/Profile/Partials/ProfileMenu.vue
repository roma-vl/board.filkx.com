<script setup>
const props = defineProps({
  activeTab: {
    type: String,
    default: '',
  },
});

const menuItems = [
  { key: 'adverts', route: 'cabinet.adverts.index' },
  { key: 'chat', route: 'cabinet.chats.index' },
  { key: 'profile', route: 'cabinet.profile.index' },
  { key: 'favorites', route: 'cabinet.favorites.index' },
  { key: 'settings', route: 'cabinet.profile.settings' },
  { key: 'banners', route: 'cabinet.banners.index' },
  { key: 'tickets', route: 'cabinet.tickets.index' },
  { key: 'pay', route: 'cabinet.orders.index' },
];
</script>

<template>
  <nav class="flex border-b mb-4">
    <template
      v-for="item in menuItems"
      :key="item.route"
    >
      <a
        :href="route(item.route)"
        class="px-4 py-2 text-gray-600 hover:text-gray-900 border-b-2 dark:text-gray-300 dark:hover:text-gray-50 dark:hover:bg-gray-800"
        :class="{
          'border-blue-500 font-bold': props.activeTab === item.route,
          'border-transparent': props.activeTab !== item.route,
        }"
      >
        {{ $t(`menu.${item.key}`) }}
      </a>
    </template>
  </nav>
</template>
