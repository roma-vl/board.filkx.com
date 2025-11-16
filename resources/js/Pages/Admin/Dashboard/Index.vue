```vue
<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatsCards from '@/Pages/Admin/Dashboard/StatsCards.vue';
import StatusCharts from '@/Pages/Admin/Dashboard/StatusCharts.vue';
import HourlyActivityChart from '@/Pages/Admin/Dashboard/HourlyActivityChart.vue';
import ModerationTable from '@/Pages/Admin/Dashboard/ModerationTable.vue';
import TodayUsersTable from '@/Pages/Admin/Dashboard/TodayUsersTable.vue';
import TodayOrdersTable from '@/Pages/Admin/Dashboard/TodayOrdersTable.vue';
import TodayAdvertsTable from '@/Pages/Admin/Dashboard/TodayAdvertsTable.vue';
import Statistic from '@/Pages/Admin/Dashboard/Statistic.vue';
import Tasks from '@/Pages/Admin/Dashboard/Tasks.vue';
import Clients from '@/Pages/Admin/Dashboard/Clients.vue';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
  todayData: {
    type: Object,
    default: () => ({}),
  },
  charts: {
    type: Object,
    default: () => ({}),
  },
  today: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="mx-auto max-w-7xl">
      <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 sm:p-8">
          <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
              Адмін панель
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
              Статистика за {{ today }}
            </p>
          </div>

            <div class="mt-8 space-y-8">
                <StatsCards :stats="stats" />
            </div>
          <div class="mt-8 space-y-8">
            <StatusCharts :charts="charts" />
            <HourlyActivityChart :hourly="charts.hourlyStats" />
          </div>

          <div class="mt-8 space-y-8">
            <ModerationTable
              :adverts="todayData.moderationAdverts"
              :today="today"
              class="mt-8"
            />
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <TodayUsersTable :users="todayData.users" />
            <TodayOrdersTable :orders="todayData.orders" />
          </div>

          <TodayAdvertsTable
            :adverts="todayData.adverts"
            class="mt-8"
          />

          <Statistic />
          <Tasks />
          <Clients />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
```
