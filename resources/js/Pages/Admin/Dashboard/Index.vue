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
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white dark:bg-gray-700 shadow-sm sm:rounded-lg p-3">
          <div class="p-4">
            <div class="mx-auto">
              <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Адмін панель
              </h1>
              <p class="text-gray-600 mb-6">
                Статистика за {{ today }}
              </p>

              <StatsCards :stats="stats" />

              <StatusCharts :charts="charts" />
              <HourlyActivityChart :hourly="charts.hourlyStats" />

              <ModerationTable
                :adverts="todayData.moderationAdverts"
                :today="today"
              />

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <TodayUsersTable :users="todayData.users" />
                <TodayOrdersTable :orders="todayData.orders" />
              </div>

              <TodayAdvertsTable :adverts="todayData.adverts" />

              <Statistic />
              <Tasks />
              <Clients />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
