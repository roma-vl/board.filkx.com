<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Account/Ticket/Create.vue';
import TicketList from '@/Components/TicketList.vue';

const tickets = computed(() => usePage().props.tickets);

const isCreateModalOpen = ref(false);
const selectedPages = ref(null);

const openCreateModal = async () => {
  isCreateModalOpen.value = true;
};
const refreshPages = () => {
  router.get(route('admin.tickets.index'), {
    preserveScroll: true,
    onSuccess: () => router.replace(route('admin.tickets.index')),
  });
};

const routes = {};
</script>

<template>
  <Head :title="$t('my.tickets')" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <ProfileMenu :active-tab="'account.tickets.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                {{ $t('my.tickets') }}
              </h2>
              <button
                class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-400 hover:text-violet-700"
                @click="openCreateModal"
              >
                + {{ $t('add.ticket') }}
              </button>
            </div>
            <TicketList
              :tickets="tickets"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
    <Modal
      :show="isCreateModalOpen"
      max-width="2xl"
      @close="isCreateModalOpen = false"
    >
      <Create
        :pages="selectedPages"
        @page-created="refreshPages"
      />
    </Modal>
  </AuthenticatedLayout>
</template>
