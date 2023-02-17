<template>
    <div class="px-4 flex items-center justify-center">
        <div ref="grid" class="grid grid-cols gap-4">
            <div
                v-for="(item, index) in items"
                :key="index"
                class="text-element bg-gray-100 p-4"
            >
                {{ item.text }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, reactive, watch } from "vue";

const items = reactive([
    { text: "Short text" },
    { text: "Medium-length text that spans a few words" },
    { text: "Longer text that spans several words and multiple lines of text" },
    { text: "Another short text" },
    { text: "Yet another text with a medium length" },
    {
        text: "And a very long text that spans multiple lines and takes up a lot of space",
    },
]);

const gridRef = ref(null);
const numColumns = ref(1);

const calculateNumColumns = () => {
    if (gridRef.value === null) return; // Check if ref is null
    const minWidth = 200;
    const maxWidth = 400;
    const containerWidth = gridRef.value.offsetWidth;
    let numCols = Math.floor(containerWidth / minWidth);
    numCols = Math.min(numCols, Math.ceil(containerWidth / maxWidth));
    numCols = Math.max(numCols, 1);
    numColumns.value = numCols;
};

onMounted(() => {
    // Wait until template is mounted before calling calculateNumColumns

    calculateNumColumns();
    window.addEventListener("resize", calculateNumColumns);
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", calculateNumColumns);
});

watch(numColumns, () => {
    const columnWidth = 100 / numColumns.value;
    items.forEach((item, index) => {
        const el = gridRef.value.children[index];
        el.style.width = `${columnWidth}%`;
    });
});
</script>
