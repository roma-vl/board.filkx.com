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
        },
        {
          label: 'Замовлення',
          data: props.hourly.map((item) => item.orders),
          borderColor: '#10B981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
        },
        {
          label: 'Оголошення',
          data: props.hourly.map((item) => item.adverts),
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
            text: 'Година',
          },
        },
        y: {
          display: true,
          title: {
            display: true,
            text: 'Кількість',
          },
          beginAtZero: true,
        },
      },
      plugins: {
        legend: {
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Активність за годинами',
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
  <div class="bg-white rounded-lg shadow mb-8 p-6 h-96">
    <canvas ref="hourlyChart" />
  </div>
</template>

<style scoped></style>
