<script setup>

import ArrowUpDownIcon from "@/Components/Icon/ArrowUpDownIcon.vue";

const props = defineProps({
    items: Array,
    headings: Array,
    uniqueKey: { type: String, default: "id" },
});

const emit = defineEmits(["sort"]);
const sortField = JSON.parse(localStorage.getItem("sortField"));

</script>

<template>
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 border-b mb-2">
        <thead class="bg-gray-50">
        <tr>
            <th v-for="heading in props.headings"
                :key="heading.key"
                @click="emit('sort', heading.key)"
                class="border-b px-3 py-3 text-gray-700  uppercase text-xs  flex-col"
                :class="{'border-b bg-gray-100 border-gray-900 text-gray-900 font-bold ': (sortField === heading.key && heading.sortable === true),
                'cursor-pointer hover:bg-gray-100': heading.sortable === true}"
            >
                <div class="flex gap-2">
                    <span class="inline-flex items-center gap-1">
                        {{ heading.value }}
                    </span>
                <span class="flex items-end gap-1 cursor-pointer text-gray-400 hover:text-gray-600"
                      :class="{'text-gray-900 font-bold': sortField === heading.key}">
                    <ArrowUpDownIcon class="w-4 h-4"  v-if="heading.sortable"/>
                </span>
                </div>
            </th>
            <th class="border-b px-3 py-3 text-gray-700 uppercase text-xs">Actions</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 border-t border-gray-100">
        <tr v-for="item in items" :key="item[uniqueKey]" class="hover:bg-gray-50">
            <td v-for="heading in props.headings" :key="heading.key" class="px-6 py-4 text-gray-600"
                :class="{' bg-gray-50 ': (sortField === heading.key && heading.sortable === true)}">
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
</template>
