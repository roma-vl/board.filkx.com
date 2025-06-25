<script setup>
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DashboardUsersIcon from '@/Components/Icon/DashboardUsersIcon.vue';
import DashboardShopIcon from '@/Components/Icon/DashboardShopIcon.vue';
import Tasks from '@/Pages/Admin/Dashboard/Tasks.vue';
import Clients from '@/Pages/Admin/Dashboard/Clients.vue';
import Statistic from '@/Pages/Admin/Dashboard/Statistic.vue';
import DashboardSalesIcon from '@/Components/Icon/DashboardSalesIcon.vue';
import { getDateFormatFromLocale, getTimeFormatFromLocale } from '@/helpers.js';
import Reject from '@/Pages/Admin/Advert/Actions/Reject.vue';
import Modal from '@/Components/Modal.vue';
import { computed, onMounted, ref } from 'vue';

const isRejectModalOpen = ref(false);
const advertId = ref(null);
const ordersChart = ref(null);
const advertsChart = ref(null);
const hourlyChart = ref(null);

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  PieController,
  DoughnutController,
  LineController,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  PieController,
  DoughnutController,
  LineController
);

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

const activate = async () => {
  router.post(route('admin.adverts.actions.moderation.active', { advert: props.advert.id }), {
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};

const rejectAdvert = () => {
  isRejectModalOpen.value = true;
  advertId.value = props.advert.id;
};

const getStatusClass = (status) => {
  const classes = {
    paid: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    failed: 'bg-red-100 text-red-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getStatusText = (status) => {
  const texts = {
    paid: 'Оплачено',
    pending: 'Очікує',
    failed: 'Помилка',
  };
  return texts[status] || status;
};

const getAdvertStatusClass = (status) => {
  const classes = {
    active: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    rejected: 'bg-red-100 text-red-800',
    expired: 'bg-gray-100 text-gray-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const orderStatusColors = {
  paid: '#4ac425',
  pending: '#F59E0B',
  failed: '#EF4444',
};

const advertStatusColors = {
  active: '#4ac425',
  moderation: '#346eeb',
  pending: '#F59E0B',
  rejected: '#EF4444',
  closed: '#317a1b',
  expired: '#6B7280',
};

const orderStatusData = computed(() => props.charts.ordersByStatus);
const advertStatusData = computed(() => props.charts.advertsByStatus);
const hourlyStats = computed(() => props.charts.hourlyStats);

const createOrdersChart = () => {
  const ctx = ordersChart.value?.getContext('2d');
  if (!ctx) return;

  const data = Object.entries(orderStatusData.value);

  new ChartJS(ctx, {
    type: 'pie',
    data: {
      labels: data.map(([status]) => getStatusText(status)),
      datasets: [
        {
          data: data.map(([, count]) => count),
          backgroundColor: data.map(([status]) => orderStatusColors[status] || '#6B7280'),
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
      },
    },
  });
};

const createAdvertsChart = () => {
  const ctx = advertsChart.value?.getContext('2d');
  if (!ctx) return;

  const data = Object.entries(advertStatusData.value);

  new ChartJS(ctx, {
    type: 'pie',
    data: {
      labels: data.map(([status]) => status),
      datasets: [
        {
          data: data.map(([, count]) => count),
          backgroundColor: data.map(([status]) => advertStatusColors[status] || '#6B7280'),
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
      },
    },
  });
};

const createHourlyChart = () => {
  const ctx = hourlyChart.value?.getContext('2d');
  if (!ctx) return;

  new ChartJS(ctx, {
    type: 'line',
    data: {
      labels: hourlyStats.value.map((item) => `${item.hour}:00`),
      datasets: [
        {
          label: 'Користувачі',
          data: hourlyStats.value.map((item) => item.users),
          borderColor: '#3B82F6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
        },
        {
          label: 'Замовлення',
          data: hourlyStats.value.map((item) => item.orders),
          borderColor: '#10B981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
        },
        {
          label: 'Оголошення',
          data: hourlyStats.value.map((item) => item.adverts),
          borderColor: '#8B5CF6',
          backgroundColor: 'rgba(139, 92, 246, 0.1)',
          tension: 0.4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        x: {
          display: true,
          title: {
            display: true,
            text: 'Години',
          },
        },
        y: {
          display: true,
          title: {
            display: true,
            text: 'Кількість',
          },
        },
      },
    },
  });
};

onMounted(async () => {
  createOrdersChart();
  createAdvertsChart();
  createHourlyChart();
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
              <!-- Заголовок -->
              <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                  Адмін панель
                </h1>
                <p class="text-gray-600">
                  Статистика за {{ today }}
                </p>
              </div>
              <!-- Statistics Cards -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 pb-6 gap-4">
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

              <!-- Графіки -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Замовлення по статусах -->
                <div class="bg-white rounded-lg shadow">
                  <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                      Замовлення по статусах
                    </h3>
                  </div>
                  <div class="p-6">
                    <div class="h-64">
                      <canvas ref="ordersChart" />
                    </div>
                  </div>
                </div>

                <!-- Оголошення по статусах -->
                <div class="bg-white rounded-lg shadow">
                  <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                      Оголошення по статусах
                    </h3>
                  </div>
                  <div class="p-6">
                    <div class="h-64">
                      <canvas ref="advertsChart" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Погодинна статистика -->
              <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">
                    Активність по годинах
                  </h3>
                </div>
                <div class="p-6">
                  <div class="h-80">
                    <canvas ref="hourlyChart" />
                  </div>
                </div>
              </div>

              <!-- Оголошення на модерації -->
              <div class="mb-8 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                  <h3 class="text-lg font-semibold text-gray-900">
                    Оголошення на модерації
                  </h3>
                  <span
                    class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full"
                  >
                    {{ todayData.moderationAdverts.length }} очікує
                  </span>
                </div>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Назва
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Автор
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Ціна
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Дата
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Дії
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr
                        v-for="advert in todayData.moderationAdverts"
                        :key="advert.id"
                      >
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          #{{ advert.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">{{
                              advert.title
                            }}</span>
                            <span
                              v-if="advert.premium"
                              class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full"
                            >
                              Premium
                            </span>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ advert.user?.name || 'Невідомо' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          ₴{{ advert.price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ getDateFormatFromLocale(advert.created_at) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <div class="flex space-x-2">
                            <button
                              class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded"
                              @click="activate"
                            >
                              {{ $t('publish') }}
                            </button>
                            <button
                              class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
                              @click="rejectAdvert"
                            >
                              {{ $t('reject') }}
                            </button>
                          </div>
                        </td>
                      </tr>
                      <tr v-if="todayData.moderationAdverts.length === 0">
                        <td
                          colspan="6"
                          class="px-6 py-4 text-center text-gray-500"
                        >
                          <div class="flex flex-col items-center">
                            <svg
                              class="w-12 h-12 text-gray-300 mb-2"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                              />
                            </svg>
                            <p>Всі оголошення перевірено!</p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Таблиці з даними -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Нові користувачі -->
                <div class="bg-white rounded-lg shadow">
                  <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                      Нові користувачі сьогодні
                    </h3>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Користувач
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Email
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Час
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                          v-for="user in todayData.users"
                          :key="user.id"
                        >
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <img
                                v-if="user.avatar_url"
                                :src="user.avatar_url"
                                :alt="user.name"
                                class="w-8 h-8 rounded-full mr-3"
                              >
                              <div
                                v-else
                                class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center"
                              >
                                <span class="text-xs text-gray-600">{{
                                  user.name.charAt(0).toUpperCase()
                                }}</span>
                              </div>
                              <span class="text-sm font-medium text-gray-900">{{ user.name }}</span>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ user.email }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ getTimeFormatFromLocale(user.created_at) }}
                          </td>
                        </tr>
                        <tr v-if="todayData.users.length === 0">
                          <td
                            colspan="3"
                            class="px-6 py-4 text-center text-gray-500"
                          >
                            Сьогодні нових користувачів немає
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Нові замовлення -->
                <div class="bg-white rounded-lg shadow">
                  <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                      Нові замовлення сьогодні
                    </h3>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            ID
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Ціна
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Статус
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                          >
                            Час
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                          v-for="order in todayData.orders"
                          :key="order.id"
                        >
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ order.id }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₴{{ order.price }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span
                              class="px-2 py-1 text-xs font-semibold rounded-full"
                              :class="getStatusClass(order.status)"
                            >
                              {{ getStatusText(order.status) }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ getTimeFormatFromLocale(order.created_at) }}
                          </td>
                        </tr>
                        <tr v-if="todayData.orders.length === 0">
                          <td
                            colspan="4"
                            class="px-6 py-4 text-center text-gray-500"
                          >
                            Сьогодні нових замовлень немає
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Оголошення -->
              <div class="mt-8 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900">
                    Нові оголошення сьогодні
                  </h3>
                </div>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Назва
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Автор
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Ціна
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Статус
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                          Час
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr
                        v-for="advert in todayData.adverts"
                        :key="advert.id"
                      >
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">{{
                              advert.title
                            }}</span>
                            <span
                              v-if="advert.premium"
                              class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full"
                            >
                              Premium
                            </span>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ advert.user?.name || 'Невідомо' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          ₴{{ advert.price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span
                            class="px-2 py-1 text-xs font-semibold rounded-full"
                            :class="getAdvertStatusClass(advert.status)"
                          >
                            {{ advert.status }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ getTimeFormatFromLocale(advert.created_at) }}
                        </td>
                      </tr>
                      <tr v-if="todayData.adverts.length === 0">
                        <td
                          colspan="5"
                          class="px-6 py-4 text-center text-gray-500"
                        >
                          Сьогодні нових оголошень немає
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- ./Statistics Cards -->

          <Statistic />

          <!-- Task Summaries -->
          <Tasks />
          <!-- ./Task Summaries -->

          <!-- Client Table -->
          <Clients />
        </div>
      </div>
    </div>
    <Modal
      :show="isRejectModalOpen"
      max-width="2xl"
      @close="isRejectModalOpen = false"
    >
      <Reject
        :advert-id="advertId"
        @reject-created="isRejectModalOpen = false"
      />
    </Modal>
  </AdminLayout>
</template>

<style scoped></style>
