<!-- src/Pages/Admin/Users/Index.vue -->
<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import DataTable from "@/Components/DataTable.vue";
import DataTableHeader from "@/Components/DataTableHeader.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";
import {useLocalStorageFn} from "@/helpers.js";



const flash = usePage().props.flash;
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);

const MIN_SEARCH_LENGTH = 1;
const PER_PAGE_DEFAULT = 2;

const headings = [
    { key: "id", value: "User ID", sortable: true },
    { key: "name", value: "Name", sortable: true },
    { key: "email", value: "Email", sortable: true },
    { key: "status", value: "Status" },
    { key: "role", value: "Role" },
    { key: "tags", value: "Tags" },
    { key: "created_at", value: "Created at" },
    { key: "updated_at", value: "Updated at" },
];

const searchQuery = useLocalStorageFn("searchQuery", "");
const perPage = useLocalStorageFn("perPage", PER_PAGE_DEFAULT);
const sortField = useLocalStorageFn("sortField", "id");
const sortOrder = useLocalStorageFn("sortOrder", "asc");
const visibleColumns = useLocalStorageFn("visibleColumns", headings.map(h => h.key));

const queryParams = computed(() => ({
    search: searchQuery.value,
    per_page: perPage.value,
    sort_by: sortField.value,
    sort_order: sortOrder.value,
}));

const updateUsersList = async () => {
    const url = queryParams.value.search ? "admin.users.search": "admin.users.index";
    await router.get(route(url), queryParams.value, { preserveScroll: true, replace: true });
};

watch(queryParams, () => {
    if (searchQuery.value && searchQuery.value.length < MIN_SEARCH_LENGTH) return;
    updateUsersList();
}, { deep: true });

const updateSorting = (field) => {
    const heading = headings.find(h => h.key === field);
    if (!heading || !heading.sortable) return;
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortOrder.value = "asc";
    }
    updateUsersList();
};

const highlightText = (text, query) => {
    if (!query) return text;
    const regex = new RegExp(`(${query})`, "gi");
    return text.replace(regex, '<span class="bg-yellow-200">$1</span>');
};
</script>

<template>
    <Head title="Users" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash" />

                <div class="mb-2 flex justify-end">
                    <Link :href="route('admin.users.create')" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">
                        + New User
                    </Link>
                </div>

                <DataTableHeader
                    :headings="headings"
                    v-model:searchQuery="searchQuery"
                    v-model:perPage="perPage"
                    v-model:visibleColumns="visibleColumns"
                    :perPageValues="[2, 5, 10, 20]"
                />

                <DataTable
                    :items="users"
                    :headings="headings.filter(h => visibleColumns.includes(h.key))"
                    uniqueKey="id"
                    @sort="updateSorting"
                >
                    <template #column-name="{ row }">
                        <div class="flex gap-2 font-normal">
                            <div class="relative h-10 w-10">
                                <div v-if="row.avatar_url">
                                    <img :src="row.avatar_url" :alt="row.name" />
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
                                <div class="font-medium text-gray-700" v-html="highlightText(row.name, searchQuery)"></div>
                            </div>
                        </div>
                    </template>
                    <template #column-email="{ row }">
                        <div class="text-sm text-gray-600" v-html="highlightText(row.email, searchQuery)"></div>
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
                <Pagination :pagination="pagination" :searchQuery="searchQuery" :sortField="sortField" :sortOrder="sortOrder" />
            </div>
        </div>
    </AdminLayout>
</template>
