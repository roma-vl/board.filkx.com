<script setup>
import { Link, router } from '@inertiajs/vue3'
import HeartIcon from '@/Components/Icon/HeartIcon.vue'
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue'
import { getFullPathForImage } from '@/helpers.js'

const props = defineProps({
    listings: {
        type: Array,
        default: () => []
    },
    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['toggle-like'])

const toggleLike = (advert) => {
    if (advert.is_favorited) {
        router.delete(route('account.favorites.remove', { advert: advert.id }), {
            replace: true,
            preserveScroll: true,
        })
    } else {
        router.post(route('account.favorites.add', { advert: advert.id }), {
            replace: true,
            preserveScroll: true,
        })
    }
    advert.is_favorited = !advert.is_favorited
    emit('toggle-like', advert)
}

// console.log(listing)
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        <!-- Skeleton Loading -->
        <template v-if="loading">
            <div
                v-for="i in 8"
                :key="i"
                class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden"
            >
                <div class="h-40 bg-gray-300 dark:bg-gray-600"></div>
                <div class="p-4 space-y-2">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                    <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/3"></div>
                </div>
            </div>
        </template>

        <!-- Cards -->
        <template v-else>
            <div
                v-for="listing in listings"
                :key="listing.id"
                class="group relative rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition duration-300 bg-white dark:bg-gray-800 flex flex-col"
            >
                <!-- Фото -->
                <div class="relative">
                    <img
                        :src="getFullPathForImage(listing.first_photo)"
                        :alt="listing.title"
                        class="w-full h-48 object-cover group-hover:scale-105 transition duration-500"
                    />

                    <!-- Бейджі -->
                    <div class="absolute top-2 left-2 flex gap-2 flex-wrap">
                    <span
                        v-if="listing.is_new"
                        class="bg-green-500 text-white text-xs px-2 py-1 rounded-full"
                    >
                      Нове
                    </span>
                    <span
                        v-if="listing.is_promo"
                        class="bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                    >
                      Акція
                    </span>
                    <slot name="badges" :listing="listing" />
                    </div>

                    <!-- Кнопка лайка -->
                    <button
                        @click="toggleLike(listing)"
                        class="absolute top-2 right-2 bg-white dark:bg-gray-700 p-1 rounded-full shadow hover:scale-110 transition"
                    >
                        <HeartIcon
                            v-if="!listing.is_favorited"
                            class="w-6 h-6 text-gray-400 hover:text-red-400 transition"
                        />
                        <HeartSolidIcon
                            v-else
                            class="w-6 h-6 text-red-500"
                        />
                    </button>
                </div>

                <!-- Контент -->
                <div class="p-4 flex flex-col justify-between flex-1">
                    <div>
                        <!-- Категорія -->
                        <p
                            v-if="listing.category"
                            class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide"
                        >
                            {{ listing.category }}
                        </p>

                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 line-clamp-2">
                            {{ listing.title }}
                        </h3>

                        <p class="text-green-600 font-bold text-md mt-1">
                            {{ listing.price }} ₴
                        </p>

                        <!-- Мета-інфа (через слот) -->
                        <slot name="meta" :listing="listing">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ listing.city }}
                            </p>
                        </slot>
                    </div>

                    <!-- Дії -->
                    <div class="flex justify-between items-center mt-4">
                        <Link
                            :href="route('adverts.show', listing.id)"
                            class="text-blue-600 hover:underline dark:text-blue-400 font-medium"
                        >
                            {{ $t('more.details') }}
                        </Link>
                        <slot name="actions" :listing="listing" />

                        <!-- Дата -->
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            {{ new Date(listing.created_at).toLocaleDateString() }}
                        </p>

                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
