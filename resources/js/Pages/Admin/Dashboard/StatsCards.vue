<script setup>
import DashboardUsersIcon from '@/Components/Icon/DashboardUsersIcon.vue';
import DashboardShopIcon from '@/Components/Icon/DashboardShopIcon.vue';
import DashboardSalesIcon from '@/Components/Icon/DashboardSalesIcon.vue';

const props = defineProps({
  stats: Object,
});
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 pb-6 gap-4">
    <!-- Користувачі -->
    <div
      class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"
    >
      <div
        class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"
      >
        <DashboardUsersIcon />
      </div>
      <div class="ml-2 text-right">
        <p class="text-sm font-medium dark:text-gray-400">
          Нові користувачі
        </p>
        <p class="text-2xl font-bold dark:text-gray-400">
          {{ stats.users.today }}
        </p>
        <p class="text-xs dark:text-gray-400">
          Всього: {{ stats.users.total }}
        </p>
      </div>
    </div>

    <!-- Замовлення -->
    <div
      class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"
    >
      <div
        class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"
      >
        <DashboardShopIcon />
      </div>
      <div class="ml-2 text-right">
        <p class="text-sm font-medium dark:text-gray-400">
          Нові замовлення
        </p>
        <p class="text-2xl font-bold dark:text-gray-400">
          {{ stats.orders.today }}
        </p>
        <p class="text-xs dark:text-gray-400">
          Всього: ₴{{ stats.orders.revenue_today }}
        </p>
      </div>
    </div>

    <!-- Оголошення -->
    <div
      class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"
    >
      <div
        class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"
      >
        <DashboardSalesIcon />
      </div>
      <div class="ml-2 text-right">
        <p class="text-sm font-medium dark:text-gray-400">
          Нові оголошення
        </p>
        <p class="text-2xl font-bold dark:text-gray-400">
          {{ stats.adverts.today }}
        </p>
        <p class="text-xs dark:text-gray-400">
          Всього: ₴{{ stats.adverts.total }}
        </p>
      </div>
    </div>

    <!-- Плейсхолдер -->
    <div
      class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"
    >
      <div
        class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"
      >
        <DashboardSalesIcon />
      </div>
      <div class="ml-2 text-right">
        <p class="text-sm font-medium dark:text-gray-400">
          Ще щось
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>

<!--<script setup>-->
<!--import DashboardUsersIcon from '@/Components/Icon/DashboardUsersIcon.vue';-->
<!--import DashboardShopIcon from '@/Components/Icon/DashboardShopIcon.vue';-->
<!--import DashboardSalesIcon from '@/Components/Icon/DashboardSalesIcon.vue';-->

<!--const props = defineProps({-->
<!--    stats: Object,-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 pb-6 gap-4">-->
<!--        &lt;!&ndash; Нові користувачі &ndash;&gt;-->
<!--        <div-->
<!--            class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"-->
<!--        >-->
<!--            <div-->
<!--                class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"-->
<!--            >-->
<!--                <DashboardUsersIcon />-->
<!--            </div>-->
<!--            <div class="ml-2 text-right">-->
<!--                <p class="text-sm font-medium dark:text-gray-400">-->
<!--                    Нові користувачі-->
<!--                </p>-->
<!--                <p class="text-2xl font-bold dark:text-gray-400">-->
<!--                    {{ stats.users.today }}-->
<!--                </p>-->
<!--                <p class="text-xs dark:text-gray-400">-->
<!--                    Всього: {{ stats.users.total }}-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Нові замовлення &ndash;&gt;-->
<!--        <div-->
<!--            class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"-->
<!--        >-->
<!--            <div-->
<!--                class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"-->
<!--            >-->
<!--                <DashboardShopIcon />-->
<!--            </div>-->
<!--            <div class="ml-2 text-right">-->
<!--                <p class="text-sm font-medium dark:text-gray-400">-->
<!--                    Нові замовлення-->
<!--                </p>-->
<!--                <p class="text-2xl font-bold dark:text-gray-400">-->
<!--                    {{ stats.orders.today }}-->
<!--                </p>-->
<!--                <p class="text-xs dark:text-gray-400">-->
<!--                    Всього: ₴{{ stats.orders.revenue_today }}-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Нові оголошення &ndash;&gt;-->
<!--        <div-->
<!--            class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"-->
<!--        >-->
<!--            <div-->
<!--                class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"-->
<!--            >-->
<!--                <DashboardSalesIcon />-->
<!--            </div>-->
<!--            <div class="ml-2 text-right">-->
<!--                <p class="text-sm font-medium dark:text-gray-400">-->
<!--                    Нові оголошення-->
<!--                </p>-->
<!--                <p class="text-2xl font-bold dark:text-gray-400">-->
<!--                    {{ stats.adverts.today }}-->
<!--                </p>-->
<!--                <p class="text-xs dark:text-gray-400">-->
<!--                    Всього: ₴{{ stats.adverts.total }}-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Ще щось &ndash;&gt;-->
<!--        <div-->
<!--            class="bg-blue-500 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-blue-600 dark:border-gray-600 text-white font-medium group"-->
<!--        >-->
<!--            <div-->
<!--                class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12"-->
<!--            >-->
<!--                <DashboardSalesIcon />-->
<!--            </div>-->
<!--            <div class="ml-2 text-right">-->
<!--                <p class="text-sm font-medium dark:text-gray-400">-->
<!--                    Ще щось-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<style scoped></style>-->
