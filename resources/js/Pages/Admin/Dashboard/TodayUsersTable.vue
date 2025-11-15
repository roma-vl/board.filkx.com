<script setup>
import { getTimeFormatFromLocale } from '@/helpers.js';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Нові користувачі сьогодні
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Користувач
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Час
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr
                    v-for="user in users"
                    :key="user.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
                >
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img
                                v-if="user.avatar_url"
                                :src="user.avatar_url"
                                :alt="user.name"
                                class="w-8 h-8 rounded-full mr-3"
                                width="32"
                                height="32"
                            >
                            <div
                                v-else
                                class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full mr-3 flex items-center justify-center"
                            >
                                <span class="text-xs text-gray-600 dark:text-gray-300">{{ user.name.charAt(0).toUpperCase() }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</span>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ user.email }}
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ getTimeFormatFromLocale(user.created_at) }}
                    </td>
                </tr>
                <tr v-if="users.length === 0">
                    <td
                        colspan="3"
                        class="px-4 sm:px-6 py-8 text-center"
                    >
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            Сьогодні нових користувачів немає
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped></style>
