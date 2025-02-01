<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {computed, ref, watch} from "vue";
import Pagination from "@/Components/Pagination.vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import DataTable from "@/Components/DataTable.vue";

const flash = usePage().props.flash;
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);

const headings = ref([
    {key: "id", value: "User ID"},
    {key: "name", value: "Name"},
    {key: "email", value: "Email"},
    {key: "status", value: "Status"},
    {key: "role", value: "Role"},
    {key: "tags", value: "Tags"},
    {key: "created_at", value: "Created at"},
    {key: "updated_at", value: "Updated at"},
]);

const headings2 = ref([
    {key: "1", value: "1"},
    {key: "2", value: "2"},
    {key: "5", value: "5"},
]);

const loadCountItems = () => {
    const countItems = localStorage.getItem("countItems");
    return countItems ? JSON.parse(countItems) : headings2.value.map(h => h.key);
};

const loadVisibleColumns = () => {
    const storedColumns = localStorage.getItem("visibleColumns");
    return storedColumns ? JSON.parse(storedColumns) : headings.value.map(h => h.key);
};

const countItems = ref(loadCountItems());
const visibleColumns = ref(loadVisibleColumns());

watch(countItems, (newValue) => {
    localStorage.setItem("countItems", JSON.stringify(newValue));
}, { deep: true });

watch(visibleColumns, (newValue) => {
    localStorage.setItem("visibleColumns", JSON.stringify(newValue));
}, {deep: true});
</script>

<template>
    <Head title="Dashboard"/>

    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash"/>
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow bg-white p-2 mb-2">
                    <div class="relative pr-2 flex justify-end">
                        <Link :href="route('admin.users.create')"
                              class="rounded-lg bg-blue-600 px-4 py-2 flex items-center border border-gray-300 hover:bg-blue-500 w-32">
                            <span class="text-white"> + New User </span>
                        </Link>
                    </div>
                </div>

                <div class=" rounded-lg border border-gray-200 shadow-md bg-white p-2">
                    <DataTable :items="users" :headings="headings" uniqueKey="id">
                        <template #column-name="{ row }">
                            <div class="flex gap-3 font-normal ">
                                <div class="relative h-10 w-10 ">
                                    <div v-if="row.avatar_url">
                                        <img :src="row.avatar_url" :alt="row.name">
                                    </div>
                                    <div v-else>
                                        <AvatarIcon />
                                    </div>

                                    <div v-if="row.status">
                                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                                    </div>
                                    <div v-else>
                                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-red-400 ring ring-white"></span>
                                    </div>
                                </div>
                                <div class="text-sm flex justify-center items-center">
                                    <div class="font-medium text-gray-700">{{ row.name }}</div>
                                </div>
                            </div>

                        </template>
                        <template #column-status="{ row }">
                            <div v-if="row.deleted_at">
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Deleted
                                </span>
                            </div>
                            <div v-else-if="row.status">
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Active
                                </span>
                            </div>
                            <div v-else>
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-semibold text-yellow-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span> Inactive
                                </span>
                            </div>
                        </template>
                        <template #column-role="{ row }">
                            Product Designer
                        </template>
                        <template #column-tags="{ row }">
                            <div class="flex gap-2">
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                                    Design
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600">
                                    Product
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-1 text-xs font-semibold text-violet-600">
                                    Develop
                                </span>
                            </div>
                        </template>
                        <template #actions="{ row }">
                            <div class="flex gap-2">
                                <div class="flex justify-end gap-4">
                                    <a href="#">
                                        <TrashIcon />
                                    </a>
                                    <a href="#">
                                        <PencilIcon />
                                    </a>
                                </div>
                            </div>
                        </template>
                    </DataTable>
                    <Pagination :pagination="pagination" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
