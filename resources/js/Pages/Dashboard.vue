<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import Breadcrumb from "@/Components/Breadcrumb.vue";
import FileUpload from "@/Components/FileUpload.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";


const flash = usePage().props.flash;
console.log(usePage(), 'flash')
const breadcrumbData = [
    {

        url: "http://localhost/",
        icon: "svg",
    },
    {
        title: "Profile",
        url: "http://localhost/dashboard/profile",
        current: true,
    },
    {
        title: "Breadcrumb without URL",
    },
];

const isDialogVisible = ref(false);

const openDialog = () => {
    isDialogVisible.value = true;
};

const closeDialog = () => {
    isDialogVisible.value = false;
};

const  mes = 'textd sad a ';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Breadcrumb :breadcrumbs="breadcrumbData" />
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>

                    <FlashMessage v-if="flash.success" type="success" :message="flash.success" />
                    <FlashMessage v-if="flash.error" type="error" :message="flash.error" />


                    <div>
                        <button @click="openDialog" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Open Dialog
                        </button>

                        <Modal
                            :show="isDialogVisible"
                            maxWidth="5xl"
                            closeable
                            @close="closeDialog"
                        >
                            <template #default>
                                <div class="p-6">
                                    <FileUpload uploadUrl="https://example.com/upload" />
                                </div>
                            </template>
                        </Modal>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
