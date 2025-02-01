<script setup>
import { ref, computed, watch } from "vue";
import ViewColumnsIcon from "@/Components/Icon/ViewColumnsIcon.vue";
import ArrowDownIcon from "@/Components/Icon/ArrowDownIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    items: Array, // Масив даних (пости, користувачі тощо)
    headings: Array, // Масив заголовків { key: "id", value: "User ID" }
    uniqueKey: { type: String, default: "id" }, // Унікальний ключ для рядків
});

const headings2 = ref([
    {key: "1", value: "1"},
    {key: "2", value: "2"},
    {key: "5", value: "5"},
]);

const isDropdownOpen = ref(false);
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};
const searchQuery = ref("");
const visibleColumns = ref(
    localStorage.getItem("visibleColumns")
        ? JSON.parse(localStorage.getItem("visibleColumns"))
        : props.headings.map(h => h.key)
);
const countItems = ref(
    localStorage.getItem("countItems")
        ? JSON.parse(localStorage.getItem("countItems"))
        : props.headings.map(h => h.key)
);

watch(countItems, (newValue) => {
    localStorage.setItem("countItems", JSON.stringify(newValue));
}, { deep: true });

watch(visibleColumns, (newValue) => {
    localStorage.setItem("visibleColumns", JSON.stringify(newValue));
}, { deep: true });

const filteredHeadings = computed(() => {
    return props.headings.filter(h => visibleColumns.value.includes(h.key));
});

</script>

<template>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex-1 pr-2">
                <input v-model="searchQuery" type="search"
                       class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none text-gray-600 font-medium border-0"
                       placeholder="Search...">
            </div>
<!--            <Dropdown align="right" width="48">-->
<!--                <template #trigger>-->
<!--                    <span class="inline-flex rounded-md">-->
<!--                        <button type="button" class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100">-->
<!--                            <ViewColumnsIcon />-->
<!--                            <ArrowDownIcon />-->
<!--                        </button>-->
<!--                    </span>-->
<!--                </template>-->

<!--                <template #content>-->
<!--                    <label v-for="heading in headings" :key="heading.key" class="flex items-center px-4 py-2 hover:bg-gray-100">-->
<!--                        <input type="checkbox" class=" mr-3 text-gray-800 rounded-sm" v-model="visibleColumns" :value="heading.key">-->
<!--                        <span>{{ heading.value }}</span>-->
<!--                    </label>-->
<!--                </template>-->
<!--            </Dropdown>-->

            <div class="relative">
                <button @click.prevent="toggleDropdown" @dblclick.prevent="toggleDropdown" class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100">
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
                <th v-for="heading in filteredHeadings" :key="heading.key" class="border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs">
                    {{ heading.value }}
                </th>
                <th class="border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-t border-gray-100">
            <tr v-for="item in items" :key="item[uniqueKey]" class="hover:bg-gray-50">
                <td v-for="heading in filteredHeadings" :key="heading.key" class="px-6 py-4 text-gray-600">
                    <slot :name="`column-${heading.key}`" :row="item">
                        {{heading.key === 'id' ?'#': '' }}  {{ item[heading.key] }}
                    </slot>
                </td>
                <td class="px-6 py-4">
                    <slot name="actions" :row="item"></slot>
                </td>
            </tr>
            </tbody>
        </table>

        <Dropdown align="right" width="48" class=" flex justify-end" >
            <template #trigger>
                <span class="inline-flex rounded-md ">
                    <button type="button" class="rounded-lg bg-white px-4 py-2 flex items-center border border-gray-300 hover:bg-gray-100">
                        count <ArrowDownIcon />
                    </button>
                </span>
            </template>

            <template #content>
                <label v-for="heading in headings2" :key="heading.key"
                       class="flex items-center px-4 py-2 hover:bg-gray-100 ">
                    <input type="checkbox" class=" mr-3 text-gray-800 rounded-sm absolute" v-model="countItems"
                           :value="heading.key">
                    <span class="ml-4">{{ heading.value }}</span>
                </label>
            </template>
        </Dropdown>
</template>
