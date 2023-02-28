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

function percentage(partialValue, totalValue) {
    return ((100 * partialValue) / totalValue).toFixed(2);
}
const municipalities = JSON.parse(localStorage.getItem("municipality"));
const positions = JSON.parse(localStorage.getItem("position"));

const grouped = groupBy(
    clusteredPrecinct.result,
    (result) => result.municipality_name
);
// console.log(grouped.get("BALABAC"));
console.log(clusteredPrecinct.result);
</script>
<template>
    <div class="bg-white px-20 py-10 mx-auto relative">
        <!-- <pre>{{ clusteredPrecinct.result }}</pre> -->
        <!-- <table
            class="table table-compact table-zebra w-full mb-12"
            v-for="municipality in municipalities"
        >
            <tr>
                <th colspan="3">{{ municipality }}</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Votes</th>
                <th>Percentage</th>
            </tr>
            <tr
                v-for="(result, index) in grouped.get(municipality)"
                :key="index"
                class="p-2 text-xs"
            >
                <td>{{ result.candidate_name }}</td>
                <td>{{ result.total_votes }}</td>
                <td>{{}}</td>
            </tr>
        </table> -->
        <div v-for="(cp, index) in clusteredPrecinct.result" :key="index">
            <h3 class="font-semibold text-2xl">{{ cp.municipality }}</h3>

            <div class="">
                Registered Voters: {{ cp.result.stats[0].reg_voters }}
            </div>
            <div class="">
                Total Turnout: {{ cp.result.stats[0].total_turnout }}
            </div>
            <div v-for="(b, index) in cp.result.turnouts" class="mt-6">
                <p class="text-xl font-semibold">{{ b.position }}</p>

                <table class="table table-compact w-full mb-20">
                    <tr>
                        <th class="w-1/3">Candidate</th>
                        <th>Votes</th>
                        <th>Percentage</th>
                    </tr>
                    <tr v-for="(c, i) in b.candidates" :key="i">
                        <td>{{ c.candidate_name }}</td>
                        <td>{{ c.total_votes }}</td>
                        <td>
                            {{
                                percentage(
                                    c.total_votes,
                                    cp.result.stats[0].total_turnout
                                )
                            }}%
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
