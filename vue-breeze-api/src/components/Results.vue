<script setup>
import { onMounted } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();

function groupBy(list, keyGetter) {
    const map = new Map();
    list.forEach((item) => {
        const key = keyGetter(item);
        const collection = map.get(key);
        if (!collection) {
            map.set(key, [item]);
        } else {
            collection.push(item);
        }
    });
    return map;
}
const municipalities = JSON.parse(localStorage.getItem("municipality"));
const positions = JSON.parse(localStorage.getItem("position"));

const grouped = groupBy(
    clusteredPrecinct.result,
    (result) => result.municipality_name
);
// console.log(grouped.get("BALABAC"));
</script>
<template>
    <div class="w-1/2 bg-white p-4 mx-auto relative">
        <!-- <pre>{{ clusteredPrecinct.result }}</pre> -->
        <table
            class="table table-compact table-zebra w-full mb-12"
            v-for="municipality in municipalities"
        >
            <tr>
                <th colspan="3">{{ municipality }}</th>
            </tr>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Votes</th>
            </tr>
            <tr
                v-for="(result, index) in grouped.get(municipality)"
                :key="index"
                class="p-2 text-xs"
            >
                <td>{{ result.candidate_position }}</td>
                <td>{{ result.candidate_name }}</td>
                <td>{{ result.total_votes }}</td>
            </tr>
        </table>
    </div>
</template>
