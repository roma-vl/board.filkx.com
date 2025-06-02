<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getFullPathForStaticImage } from '@/helpers.js';
</script>

<template>
  <Head :title="$t('contact.page_title')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg dark:text-gray-200 dark:bg-gray-700"
        >
          <div class="container mx-auto dark:text-gray-200 dark:bg-gray-800">
            <div class="max-w-md mx-auto">
              <label
                for="select"
                class="font-semibold block py-2"
              >Select Input:</label>

              <div class="relative dark:bg-gray-900">
                <div
                  class="h-10 bg-white flex border border-gray-200 dark:border-gray-900 rounded items-center dark:bg-gray-900"
                >
                  <input
                    id="select"
                    value="Javascript"
                    name="select"
                    class="px-4 appearance-none outline-none text-gray-800 w-full dark:border-gray-900 dark:bg-gray-900 dark:text-gray-200"
                    checked
                  >

                  <button
                    class="cursor-pointer outline-none focus:outline-none transition-all text-gray-300 hover:text-gray-600"
                  >
                    <svg
                      class="w-4 h-4 mx-2 fill-current"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <line
                        x1="18"
                        y1="6"
                        x2="6"
                        y2="18"
                      />
                      <line
                        x1="6"
                        y1="6"
                        x2="18"
                        y2="18"
                      />
                    </svg>
                  </button>
                  <label
                    for="show_more"
                    class="cursor-pointer outline-none focus:outline-none border-l border-gray-200 transition-all text-gray-300 hover:text-gray-600"
                  >
                    <svg
                      class="w-4 h-4 mx-2 fill-current"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <polyline points="18 15 12 9 6 15" />
                    </svg>
                  </label>
                </div>

                <input
                  id="show_more"
                  type="checkbox"
                  name="show_more"
                  class="hidden peer"
                >
                <div
                  class="absolute rounded shadow bg-white overflow-hidden hidden peer-checked:flex flex-col w-full mt-1 border border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:text-gray-200"
                >
                  <div class="cursor-pointer group border-t">
                    <a
                      class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-100"
                    >Node</a>
                  </div>
                  <div class="cursor-pointer group border-t">
                    <a
                      class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-800"
                    >PHP</a>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="max-w-screen-xl mx-auto px-5 bg-white min-h-sceen dark:text-gray-200 dark:bg-gray-800"
            >
              <div class="flex flex-col items-center">
                <h2 class="font-bold text-5xl mt-5 tracking-tight">
                  FAQ
                </h2>
                <p class="text-neutral-500 text-xl mt-3 dark:text-gray-200">
                  Frequenty asked questions
                </p>
              </div>
              <div class="grid divide-y divide-neutral-200 max-w-xl mx-auto mt-8">
                <div class="py-5">
                  <details class="group">
                    <summary
                      class="flex justify-between items-center font-medium cursor-pointer list-none"
                    >
                      <span> What is a SAAS platform?</span>
                      <span class="transition group-open:rotate-180">
                        <svg
                          fill="none"
                          height="24"
                          shape-rendering="geometricPrecision"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          viewBox="0 0 24 24"
                          width="24"
                        >
                          <path d="M6 9l6 6 6-6" />
                        </svg>
                      </span>
                    </summary>
                    <p class="text-neutral-600 mt-3 group-open:animate-fadeIn dark:text-gray-300">
                      SAAS platform is a cloud-based software service that allows users to access
                      and use a variety of tools and functionality.
                    </p>
                  </details>
                </div>
                <div class="py-5">
                  <details class="group">
                    <summary
                      class="flex justify-between items-center font-medium cursor-pointer list-none"
                    >
                      <span> How does billing work?</span>
                      <span class="transition group-open:rotate-180">
                        <svg
                          fill="none"
                          height="24"
                          shape-rendering="geometricPrecision"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          viewBox="0 0 24 24"
                          width="24"
                        >
                          <path d="M6 9l6 6 6-6" />
                        </svg>
                      </span>
                    </summary>
                    <p class="text-neutral-600 mt-3 group-open:animate-fadeIn dark:text-gray-300">
                      We offers a variety of billing options, including monthly and annual
                      subscription plans, as well as pay-as-you-go pricing for certain services.
                      Payment is typically made through a credit card or other secure online payment
                      method.
                    </p>
                  </details>
                </div>
              </div>
            </div>

            <div
              class="mx-auto flex max-w-sm items-center gap-x-4 rounded-xl bg-white p-6 shadow-lg outline outline-black/5 dark:bg-slate-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
            >
              <img
                class="size-12 shrink-0"
                src="/img/logo.svg"
                alt="ChitChat Logo"
              >
              <div>
                <div class="text-xl font-medium text-black dark:text-white">
                  ChitChat
                </div>
                <p class="text-gray-500 dark:text-gray-400">
                  You have a new message!
                </p>
              </div>
            </div>

            <button
              type="button"
              data-twe-ripple-init
              data-twe-ripple-color="light"
              class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
            >
              Click me
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
