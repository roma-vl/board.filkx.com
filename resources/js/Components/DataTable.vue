<script setup>

const props = defineProps({
    items: Array,
    headings: Array,
    uniqueKey: { type: String, default: "id" },
});

</script>

<template>
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 border-b mb-2">
        <thead class="bg-gray-50">
        <tr>
            <th v-for="heading in props.headings" :key="heading.key" class="border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs">
                {{ heading.value }}
            </th>
            <th class="border-b px-3 py-3 text-gray-900 font-bold uppercase text-xs">Actions</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 border-t border-gray-100">
        <tr v-for="item in items" :key="item[uniqueKey]" class="hover:bg-gray-50">
            <td v-for="heading in props.headings" :key="heading.key" class="px-6 py-4 text-gray-600">
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
