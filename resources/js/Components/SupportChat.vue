<script setup>
import { ref } from 'vue';

const isMessagesVisible = ref(false);

const messages = () => {
  isMessagesVisible.value = !isMessagesVisible.value;
};
</script>

<template>
  <div>
    <button
      class="z-20 text-white flex flex-col shrink-0 grow-0 justify-around fixed bottom-0 right-1 rounded-lgmr-3 mb-3"
      @click="messages"
    >
      <div class="p-3 rounded-full border-4 border-white bg-green-600">
        <svg
          class="w-6 h-6 lg:w-8 lg:h-8 xl:w-10 xl:h-10"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
    </button>
    <div
      v-if="isMessagesVisible"
      class="fixed flex-col space-y-4 mb-20 right-4 md:bottom-8 lg:mb-16 z-5 flex"
    >
      <div class="w-full h-auto relative">
        <div
          class="bg-white px-5 py-3.5 rounded-lg shadow hover:shadow-md max-w-sm mx-auto transform hover:-translate-y-[0.125rem] transition duration-100 ease-linear"
        >
          <div class="w-full flex items-center justify-between">
            <span class="font-medium text-sm text-slate-400">New Notification</span>
            <button
              class="-mr-1 bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 h-5 w-5 rounded-full flex justify-center items-center"
            >
              <svg
                class="h-2 w-2 fill-current items-center"
                viewBox="0 0 20 20"
              >
                <path
                  d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"
                />
              </svg>
            </button>
          </div>
          <div class="flex items-center mt-2 rounded-lg px-1 py-1 cursor-pointer">
            <div class="relative flex flex-shrink-0 items-end">
              <img
                class="h-16 w-16 rounded-full"
                src="https://i.pravatar.cc/300"
                alt=""
              >
              <span
                class="absolute h-4 w-4 bg-green-400 rounded-full bottom-0 right-0 border-2 border-white"
              />
            </div>
            <div class="ml-3">
              <span class="font-semibold tracking-tight text-xs">John Doe</span>
              <span class="text-xs leading-none opacity-50"> reacted to your comment:</span>
              <p class="text-xs leading-4 pt-2 italic opacity-70">
                "This is the comment..."
              </p>
              <span class="text-[10px] text-blue-500 font-medium leading-4 opacity-75">a few seconds ago</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
