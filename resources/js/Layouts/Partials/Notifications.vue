<script setup>

import Dropdown from "@/Components/Dropdown.vue";
</script>

<template>
    <Dropdown align="right" width="72">
        <template #trigger>
            <div class="flex items-center justify-center rounded-lg p-2 font-sans text-xs cursor-pointer font-bold uppercase text-pink-500 transition-all hover:bg-pink-500/10 active:bg-pink-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                </svg>
            </div>
        </template>

        <template #content>
            <ul class="  flex-col gap-2 overflow-auto  bg-white p-3  font-sans text-sm font-normal text-blue-gray-500 shadow-blue-gray-500/10 focus:outline-none">
                <div role="menuitem" class=" flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300  active:bg-gray-300 active:border-gray-800">
                    <img alt="tania andrew"
                         src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1480&amp;q=80"
                         class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"/>
                    <div class="flex flex-col gap-1">
                        <p class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased">
                            <span class="font-medium text-blue-gray-900">Tania</span> send you a message
                        </p>
                        <p class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                                class="h-3 w-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            13 minutes ago
                        </p>
                    </div>
                </div>
                <div class="flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300  active:bg-gray-300 active:border-gray-800">
                    <img
                        alt="natali craig"
                        src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1061&amp;q=80"
                        class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"/>
                    <div class="flex flex-col gap-1">
                        <p class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased">
                            <span class="font-medium text-blue-gray-900">Natali</span> reply to your email
                        </p>
                        <p class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                                class="h-3 w-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            a hour ago
                        </p>
                    </div>
                </div>

            </ul>
        </template>
    </Dropdown>
</template>

<style scoped>

</style>
