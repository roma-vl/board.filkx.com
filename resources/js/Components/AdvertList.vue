<script setup>
import { getDateFormatFromLocale, getFullPathForImage } from '@/helpers.js';
import { router } from '@inertiajs/vue3';
import BullhornIcon from '@/Components/Icon/BullhornIcon.vue';
import EyeIcon from '@/Components/Icon/EyeIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';

const props = defineProps({
  adverts: {
    type: Object,
    default: () => ({}),
  },
  routes: {
    type: Object,
    default: () => ({}),
  },
});
const remove = (advertId) => {
  router.delete(route(props.routes.remove, { advert: advertId }));
};
</script>

<template>
  <div class="bg-white overflow-hidden mb-4 dark:bg-gray-700 rounded-md shadow-md">
    <div
      v-if="props.adverts.data.length"
      class="divide-y divide-gray-100"
    >
      <div
        v-for="advert in adverts.data"
        :key="advert.id"
        class="p-3 hover:bg-gray-100 transition duration-150 ease-in-out dark:hover:bg-gray-800/50 dark:bg-gray-800"
      >
        <div class="flex justify-between min-h-36">
          <div class="w-[340px]">
            <img
              :src="getFullPathForImage(advert.firstPhoto)"
              :alt="advert.title"
              class="w-full h-40 object-cover"
            >
          </div>
          <div class="flex-grow flex flex-col justify-between w-full pl-3">
            <div>
              <a
                :href="route('adverts.show', advert.id)"
                class="block group"
              >
                <h3
                  class="text-2xl font-semibold text-gray-800 group-hover:text-violet-600 transition-colors duration-200 dark:text-gray-200"
                >
                  {{ advert.title }}
                </h3>
              </a>
              <p class="text-xl font-medium text-violet-600">
                {{ advert.price }} ₴
              </p>
            </div>
            <div class="flex items-center gap-6 text-sm mt-4">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-200">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path
                    fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"
                  />
                </svg>
                6 {{ $t('views') }}
              </span>
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-200">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                2 {{ $t('in.bookmarks') }}
              </span>
              <p class="text-sm text-gray-500 dark:text-gray-200">
                {{ $t('Created At') }} : {{ getDateFormatFromLocale(advert.createdAt) }}
                {{ console.log(advert.createdAt) }}
              </p>
            </div>
          </div>
          <div class="flex flex-col items-end justify-between">
            <span
              class="px-2 py-1 rounded text-sm font-medium"
              :style="{ backgroundColor: advert.status?.color, color: '#fff' }"
            >
              {{ advert.status?.name }}
            </span>
            <div
              v-if="props.routes"
              class="flex items-center gap-3 mt-auto"
            >
              <a
                v-if="props.routes.promote"
                :href="route(props.routes.promote, advert.id)"
                class="p-2 text-gray-100 hover:text-gray-50 hover:bg-violet-700 rounded-lg transition duration-150 group bg-violet-600"
              >
                <span class="flex">
                  <p class="pr-2">Рекламувати</p>

                  <BullhornIcon class="text-gray-100" />
                </span>
              </a>
              <a
                v-if="props.routes.edit"
                :href="route(props.routes.edit, advert.id)"
                title="Редагувати"
                class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Редагувати
                </span>
                <PencilIcon />
              </a>
              <a
                v-if="props.routes.show"
                :href="route(props.routes.show, advert.id)"
                title="Переглянути"
                class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Переглянути
                </span>
                <EyeIcon />
              </a>
              <button
                v-if="props.routes.remove"
                title="Видалити"
                class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-150 group"
                @click="remove(advert.id)"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Видалити
                </span>
                <TrashIcon />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <p
      v-else
      class="py-12 text-center text-gray-500 text-lg"
    >
      У вас немає оголошень.
    </p>
  </div>
</template>
<style scoped></style>
