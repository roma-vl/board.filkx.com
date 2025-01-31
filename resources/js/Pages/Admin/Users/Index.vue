<script setup>

import {Head, Link, usePage} from "@inertiajs/vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {computed, ref, watch} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import Pagination from "@/Components/Pagination.vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";
import ViewColumnsIcon from "@/Components/Icon/ViewColumnsIcon.vue";
import ArrowDownIcon from "@/Components/Icon/ArrowDownIcon.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";

const flash = usePage().props.flash;
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);
console.log(pagination)

const isDropdownOpen = ref(false);
const searchQuery = ref("");

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
const loadVisibleColumns = () => {
    const storedColumns = localStorage.getItem("visibleColumns");
    return storedColumns ? JSON.parse(storedColumns) : headings.value.map(h => h.key);
};

const visibleColumns = ref(loadVisibleColumns());


const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const filteredHeadings = computed(() => {
    return headings.value.filter(h => visibleColumns.value.includes(h.key));
});

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users;
    return users.filter(user =>
        Object.values(user).some(val =>
            String(val).toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    );
});

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


                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md bg-white p-2">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 pr-2">
                            <input v-model="searchQuery" type="search"
                                   class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none text-gray-600 font-medium border-0"
                                   placeholder="Search...">
                        </div>
                        <div class="relative">
                            <button @click="toggleDropdown" class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100">
                                <ViewColumnsIcon />
                                <ArrowDownIcon />
                            </button>

                            <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                <label v-for="heading in headings" :key="heading.key" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                    <input type="checkbox" class=" mr-3 text-gray-800 rounded-sm" v-model="visibleColumns" :value="heading.key">
                                    <span>{{ heading.value }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 border-b mb-2">
                        <thead class="bg-gray-50">
                        <tr>
                            <th v-for="heading in filteredHeadings" :key="heading.key"
                                class=" border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs">
                                {{ heading.value }}
                            </th>
                            <th class=" border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">

                            <td v-if="filteredHeadings.some(h => h.key === 'id')" class="px-6 py-4">
                                <div class="text-gray-900">#{{ user.id }}</div>
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'name')" class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                                <div class="relative h-10 w-10">
                                    <div v-if="user.avatar_url">
                                        <img :src="user.avatar_url" :alt="user.name">
                                    </div>
                                    <div v-else>
                                      <AvatarIcon />
                                    </div>

                                    <div v-if="user.status">
                                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                                    </div>
                                    <div v-else>
                                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-red-400 ring ring-white"></span>
                                    </div>

                                </div>
                                <div class="text-sm flex justify-center items-center">
                                    <div class="font-medium text-gray-700">{{ user.name }}</div>
                                </div>
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'email')" class="px-6 py-4">
                                <div class="text-gray-400">{{ user.email }}</div>
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'status')" class="px-6 py-4">
                                <div v-if="user.deleted_at">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Deleted </span>

                                </div>
                                <div v-else-if="user.status">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Active </span>
                                </div>
                                <div v-else>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-semibold text-yellow-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span> Inactive </span>
                                </div>
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'role')" class="px-6 py-4">
                                Product Designer
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'tags')" class="px-6 py-4">
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
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'created_at')" class="px-6 py-4">
                                {{ user.created_at }}
                            </td>
                            <td v-if="filteredHeadings.some(h => h.key === 'updated_at')" class="px-6 py-4">
                                {{ user.updated_at }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">
                                    <a href="#">
                                        <TrashIcon />
                                    </a>
                                    <a href="#">
                                        <PencilIcon />
                                    </a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 pr-2">
                            <p class="p-2 text-gray-600 font-medium "> Всього: {{ pagination.total }}</p>
                        </div>

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                  <span class="inline-flex rounded-md">
                                      <button type="button" class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100">
                                          Display <ArrowDownIcon />
                                      </button>
                                  </span>
                            </template>

                            <template #content>
                                <label v-for="heading in headings2" :key="heading.key"
                                       class="flex items-center px-4 py-2 hover:bg-gray-100 ">
                                    <input type="checkbox" class=" mr-3 text-gray-800 rounded-sm absolute"
                                           v-model="visibleColumns"
                                           :value="heading.key">
                                    <span class="ml-4">{{ heading.value }}</span>
                                </label>
                            </template>
                        </Dropdown>
                    </div>

                    <Pagination :pagination="pagination" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>
