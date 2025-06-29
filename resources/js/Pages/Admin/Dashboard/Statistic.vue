<script setup lang="ts"></script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 p-4 gap-4">
    <!-- Social Traffic -->
    <div
      class="flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 dark:bg-gray-800 w-full shadow-lg rounded"
    >
      <div class="rounded-t mb-0 px-0 border-0">
        <div class="flex flex-wrap items-center px-4 py-2">
          <div class="w-full max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">
              Social Traffic
            </h3>
          </div>
          <div class="w-full max-w-full flex-grow flex-1 text-right">
            <button
              class="bg-blue-500 dark:bg-gray-100 text-white active:bg-blue-600 dark:text-gray-800 dark:active:text-gray-700 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button"
            >
              See all
            </button>
          </div>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th
                  class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Referral
                </th>
                <th
                  class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                >
                  Visitors
                </th>
                <th
                  class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left min-w-140-px"
                />
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 dark:text-gray-100">
                <th
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  Facebook
                </th>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  5,480
                </td>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <div class="flex items-center">
                    <span class="mr-2">70%</span>
                    <div class="w-full">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                        <div
                          style="width: 70%"
                          class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600"
                        />
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-100">
                <th
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  Twitter
                </th>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  3,380
                </td>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <div class="flex items-center">
                    <span class="mr-2">40%</span>
                    <div class="w-full">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                        <div
                          style="width: 40%"
                          class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"
                        />
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-100">
                <th
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  Instagram
                </th>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  4,105
                </td>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <div class="flex items-center">
                    <span class="mr-2">45%</span>
                    <div class="w-full">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-pink-200">
                        <div
                          style="width: 45%"
                          class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500"
                        />
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-100">
                <th
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  Google
                </th>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  4,985
                </td>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <div class="flex items-center">
                    <span class="mr-2">60%</span>
                    <div class="w-full">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-red-200">
                        <div
                          style="width: 60%"
                          class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"
                        />
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-100">
                <th
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  Linkedin
                </th>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  2,250
                </td>
                <td
                  class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <div class="flex items-center">
                    <span class="mr-2">30%</span>
                    <div class="w-full">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                        <div
                          style="width: 30%"
                          class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-700"
                        />
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- ./Social Traffic -->

    <!-- Recent Activities -->
    <div
      class="flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full shadow-lg rounded"
    >
      <div class="rounded-t mb-0 px-0 border-0">
        <div class="flex flex-wrap items-center px-4 py-2">
          <div class="w-full max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">
              Recent Activities
            </h3>
          </div>
          <div class="w-full max-w-full flex-grow flex-1 text-right">
            <button
              class="bg-blue-500 dark:bg-gray-100 text-white active:bg-blue-600 dark:text-gray-800 dark:active:text-gray-700 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button"
            >
              See all
            </button>
          </div>
        </div>
        <div class="block w-full">
          <div
            class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
          >
            Today
          </div>
          <ul class="my-1">
            <li class="flex px-4">
              <div class="w-9 h-9 rounded-full flex-shrink-0 bg-indigo-500 my-2 mr-3">
                <svg
                  class="w-9 h-9 fill-current text-indigo-50"
                  viewBox="0 0 36 36"
                >
                  <path
                    d="M18 10c-4.4 0-8 3.1-8 7s3.6 7 8 7h.6l5.4 2v-4.4c1.2-1.2 2-2.8 2-4.6 0-3.9-3.6-7-8-7zm4 10.8v2.3L18.9 22H18c-3.3 0-6-2.2-6-5s2.7-5 6-5 6 2.2 6 5c0 2.2-2 3.8-2 3.8z"
                  />
                </svg>
              </div>
              <div
                class="flex-grow flex items-center border-b border-gray-100 dark:border-gray-400 text-sm text-gray-600 dark:text-gray-100 py-2"
              >
                <div class="flex-grow flex justify-between items-center">
                  <div class="self-center">
                    <a
                      class="font-medium text-gray-800 hover:text-gray-900 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >
                      Nick Mark</a>mentioned
                    <a
                      class="font-medium text-gray-800 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >
                      Sara Smith</a>
                    in a new post
                  </div>
                  <div class="flex-shrink-0 ml-2">
                    <a
                      class="flex items-center font-medium text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500"
                      href="#"
                      style="outline: none"
                    >
                      View
                      <span>
                        <svg
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                          class="transform transition-transform duration-500 ease-in-out"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </li>
            <li class="flex px-4">
              <div class="w-9 h-9 rounded-full flex-shrink-0 bg-red-500 my-2 mr-3">
                <svg
                  class="w-9 h-9 fill-current text-red-50"
                  viewBox="0 0 36 36"
                >
                  <path d="M25 24H11a1 1 0 01-1-1v-5h2v4h12v-4h2v5a1 1 0 01-1 1zM14 13h8v2h-8z" />
                </svg>
              </div>
              <div
                class="flex-grow flex items-center border-gray-100 text-sm text-gray-600 dark:text-gray-50 py-2"
              >
                <div class="flex-grow flex justify-between items-center">
                  <div class="self-center">
                    The post
                    <a
                      class="font-medium text-gray-800 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >
                      Post Name
                    </a>
                    was removed by
                    <a
                      class="font-medium text-gray-800 hover:text-gray-900 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >Nick Mark</a>
                  </div>
                  <div class="flex-shrink-0 ml-2">
                    <a
                      class="flex items-center font-medium text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500"
                      href="#"
                      style="outline: none"
                    >
                      View<span><svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="transform transition-transform duration-500 ease-in-out"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd"
                        /></svg></span>
                    </a>
                  </div>
                </div>
              </div>
            </li>
          </ul>
          <div
            class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
          >
            Yesterday
          </div>
          <ul class="my-1">
            <li class="flex px-4">
              <div class="w-9 h-9 rounded-full flex-shrink-0 bg-green-500 my-2 mr-3">
                <svg
                  class="w-9 h-9 fill-current text-light-blue-50"
                  viewBox="0 0 36 36"
                >
                  <path
                    d="M23 11v2.085c-2.841.401-4.41 2.462-5.8 4.315-1.449 1.932-2.7 3.6-5.2 3.6h-1v2h1c3.5 0 5.253-2.338 6.8-4.4 1.449-1.932 2.7-3.6 5.2-3.6h3l-4-4zM15.406 16.455c.066-.087.125-.162.194-.254.314-.419.656-.872 1.033-1.33C15.475 13.802 14.038 13 12 13h-1v2h1c1.471 0 2.505.586 3.406 1.455zM24 21c-1.471 0-2.505-.586-3.406-1.455-.066.087-.125.162-.194.254-.316.422-.656.873-1.028 1.328.959.878 2.108 1.573 3.628 1.788V25l4-4h-3z"
                  />
                </svg>
              </div>
              <div
                class="flex-grow flex items-center border-gray-100 text-sm text-gray-600 dark:text-gray-50 py-2"
              >
                <div class="flex-grow flex justify-between items-center">
                  <div class="self-center">
                    <a
                      class="font-medium text-gray-800 hover:text-gray-900 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >240+</a>
                    users have subscribed to
                    <a
                      class="font-medium text-gray-800 dark:text-gray-50 dark:hover:text-gray-100"
                      href="#"
                      style="outline: none"
                    >Newsletter #1</a>
                  </div>
                  <div class="flex-shrink-0 ml-2">
                    <a
                      class="flex items-center font-medium text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500"
                      href="#"
                      style="outline: none"
                    >
                      View<span><svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="transform transition-transform duration-500 ease-in-out"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd"
                        /></svg></span>
                    </a>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- ./Recent Activities -->
  </div>
</template>

<style scoped></style>
