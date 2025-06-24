<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed, ref } from 'vue';
import OrderList from '@/Pages/Account/Orders/OrderList.vue';

const orders = computed(() => usePage().props.orders);

const routes = {
  receipt: 'account.orders.receipt',
};
</script>

<template>
  <Head :title="$t('my.orders')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white sm:rounded-lg p-3 dark:bg-gray-700 rounded-md shadow-md"
        >
          <ProfileMenu :active-tab="'account.orders.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                {{ $t('my.orders') }}
              </h2>
            </div>
            <OrderList
              :orders="orders"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
