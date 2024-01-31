<template>
    <div>
        <input v-model="searchQuery" placeholder="Search..." />
        <div v-for="(group, index) in filteredData" :key="index">
            <h2>{{ group.category }}</h2>
            <ul>
                <li v-for="item in group.filteredItems" :key="item.id">
                    {{ item.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const searchQuery = ref("");
const data = [
    {
        category: "Category A",
        items: [
            { id: 1, name: "Item 1" },
            { id: 2, name: "Item 2" },
        ],
    },
    {
        category: "Category B",
        items: [
            { id: 3, name: "Item 3" },
            { id: 4, name: "Item 4" },
        ],
    },
    // ... other data
];

const filteredData = computed(() => {
    const lowerCaseQuery = searchQuery.value.toLowerCase();
    return data
        .map((group) => {
            const filteredItems = group.items.filter((item) =>
                item.name.toLowerCase().includes(lowerCaseQuery)
            );
            return { category: group.category, filteredItems };
        })
        .filter((group) => {
            // Filter groups based on the category or filtered items
            return (
                group.category.toLowerCase().includes(lowerCaseQuery) ||
                group.filteredItems.length > 0
            );
        });
});
</script>
