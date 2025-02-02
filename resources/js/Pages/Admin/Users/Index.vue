<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch, onMounted } from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import DataTable from "@/Components/DataTable.vue";
import Dropdown from "@/Components/Dropdown.vue";
import ArrowDownIcon from "@/Components/Icon/ArrowDownIcon.vue";
import ViewColumnsIcon from "@/Components/Icon/ViewColumnsIcon.vue";

const useLocalStorage = (key, defaultValue) => {
    let stored;
    try {
        stored = JSON.parse(localStorage.getItem(key));
    } catch (error) {
        stored = null;
    }
    const value = ref(stored ?? defaultValue);

    watch(value, (newValue) => {
        localStorage.setItem(key, JSON.stringify(newValue));
    }, { deep: true });

    return value;
};


const flash = usePage().props.flash;
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);
const MIN_SEARCH_LENGTH = 1;
const PER_PAGE_DEFAULT = 2;
const PER_PAGE_VALUES = [2, 5, 10, 20];

const headings = ref([
    { key: "id", value: "User ID" , sortable: true},
    { key: "name", value: "Name" , sortable: true},
    { key: "email", value: "Email" , sortable: true},
    { key: "status", value: "Status" },
    { key: "role", value: "Role" },
    { key: "tags", value: "Tags" },
    { key: "created_at", value: "Created at" },
    { key: "updated_at", value: "Updated at" },
]);

const visibleColumns = useLocalStorage("visibleColumns", headings.value.map(h => h.key));
const perPage = useLocalStorage("perPage", PER_PAGE_DEFAULT);
const sortField = useLocalStorage("sortField", "id");
const sortOrder = useLocalStorage("sortOrder", "asc");

// Стан пошукового запиту – беремо з URL, якщо є
const urlParams = new URLSearchParams(window.location.search);
const searchQuery = ref(urlParams.get("search") || "");

const isDropdownOpen = ref(false);
const toggleDropdown = () => isDropdownOpen.value = !isDropdownOpen.value;


const searchInputRef = ref(null);

const highlightText = (text, query) => query ? text.replace(new RegExp(`(${query})`, "gi"), '<span class="bg-yellow-200">$1</span>') : text;

const isLoading = ref(false);

const updateUsersList = async () => {
    if (isLoading.value) return;

    isLoading.value = true;
    const url = queryParams.value.search ? "admin.users.search": "admin.users.index";
    await router.get(route(url), queryParams.value, { preserveScroll: true, replace: true });
    isLoading.value = false;
};



const queryParams = computed(() => ({
    search: searchQuery.value,
    per_page: perPage.value,
    sort_by: sortField.value,
    sort_order: sortOrder.value,
}));

watch(queryParams, () => {
    if (searchQuery.value && searchQuery.value.length < MIN_SEARCH_LENGTH) return;
    updateUsersList();
}, { deep: true });

onMounted(() => {
    if (searchInputRef.value) {
        searchInputRef.value.focus();
    }
});

const updateSorting = (field) => {
    const heading = headings.value.find(h => h.key === field);

    if (!heading || !heading.sortable) return;

    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortOrder.value = "asc";
    }

    updateUsersList();
};

</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash" />
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow bg-white p-2 mb-2">
                    <div class="relative flex justify-end">
                        <Link
                            :href="route('admin.users.create')"
                            class="rounded-lg bg-blue-600 px-4 py-2 flex items-center border border-gray-300 hover:bg-blue-500 w-32"
                        >
                            <span class="text-white"> + New User </span>
                        </Link>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 shadow-md bg-white p-2">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 pr-2">
                            <input
                                ref="searchInputRef"
                                v-model="searchQuery"
                                type="search"
                                class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none text-gray-600 font-medium border-0"
                                placeholder="Search..."
                            />
                        </div>

                        <div class="relative mr-2">
                            <button
                                @click.prevent="toggleDropdown"
                                @dblclick.prevent="toggleDropdown"
                                class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100"
                            >
                                <ViewColumnsIcon />
                                <ArrowDownIcon />
                            </button>

                            <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                <label v-for="heading in headings" :key="heading.key" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                    <input type="checkbox" class="mr-3 text-gray-800 rounded-sm" v-model="visibleColumns" :value="heading.key" />
                                    <span>{{ heading.value }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="relative">
                            <Dropdown align="right" width="24" class="flex justify-end">
                                <template #trigger>
                                    <button class="rounded-lg bg-white px-4 py-2 flex items-center shadow hover:bg-gray-100">
                                        {{ perPage }} <ArrowDownIcon />
                                    </button>
                                </template>
                                <template #content>
                                    <label v-for="count in PER_PAGE_VALUES" :key="count" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                        <input type="radio" v-model="perPage" :value="count" class="mr-3" />
                                        {{ count }}
                                    </label>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Таблиця -->
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
        </div>
    </AdminLayout>
</template>
