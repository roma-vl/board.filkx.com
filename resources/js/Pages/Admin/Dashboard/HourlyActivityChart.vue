<script setup>
import { ref, onMounted, watch } from 'vue';
import {
  Chart,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  LineController,
} from 'chart.js';

Chart.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  LineController
);

const props = defineProps({
  hourly: {
    type: Array,
    default: () => [],
  },
});

const hourlyChart = ref(null);
let chartInstance = null;

const createHourlyChart = () => {
  if (chartInstance) chartInstance.destroy();

  const ctx = hourlyChart.value.getContext('2d');
  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: props.hourly.map((item) => `${item.hour}:00`),
      datasets: [
        {
          label: 'Користувачі',
          data: props.hourly.map((item) => item.users),
          borderColor: '#3B82F6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true,
        },
        {
          label: 'Замовлення',
          data: props.hourly.map((item) => item.orders),
          borderColor: '#10B981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true,
        },
        {
          label: 'Оголошення',
          data: props.hourly.map((item) => item.adverts),
          borderColor: '#8B5CF6',
          backgroundColor: 'rgba(139, 92, 246, 0.1)',
          tension: 0.4,
          fill: true,
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
            text: 'Година',
            color: '#6B7280',
          },
          ticks: {
            color: '#6B7280',
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)',
          },
        },
        y: {
          display: true,
          title: {
            display: true,
            text: 'Кількість',
            color: '#6B7280',
          },
          ticks: {
            color: '#6B7280',
          },
          beginAtZero: true,
          grid: {
            color: 'rgba(0, 0, 0, 0.05)',
          },
        },
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#6B7280',
            font: {
              size: 12,
            },
            padding: 20,
          },
        },
        title: {
          display: true,
          text: 'Активність за годинами',
          color: '#1F2937',
          font: {
            size: 16,
            weight: 'bold',
          },
        },
        tooltip: {
          bodyColor: '#1F2937',
          titleColor: '#1F2937',
          backgroundColor: '#FFFFFF',
          borderColor: '#E5E7EB',
          borderWidth: 1,
        },
      },
    },
  });
};

onMounted(() => {
  createHourlyChart();
});

watch(
  () => props.hourly,
  () => {
    createHourlyChart();
  }
);
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 h-80 sm:h-96"
  >
    <canvas ref="hourlyChart" />
  </div>
</template>

<style scoped></style>
