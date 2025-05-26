<script setup>
const props = defineProps({
    activeTab: {
        type: String,
        default: '',
    },
});

const menuItems = [
    { key: 'adverts', route: 'account.adverts.index' },
    { key: 'chat', route: 'account.chats.index' },
    { key: 'profile', route: 'account.profile.index' },
    { key: 'favorites', route: 'account.favorites.index' },
    { key: 'settings', route: 'account.profile.settings' },
    { key: 'banners', route: 'account.banners.index' },
    { key: 'tickets', route: 'account.tickets.index' },
];
</script>

<template>
    <nav class="flex border-b mb-4">
        <template v-for="item in menuItems" :key="item.route">
            <a
                :href="route(item.route)"
                class="px-4 py-2 text-gray-600 hover:text-gray-900 border-b-2"
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
