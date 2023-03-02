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

function sortTotalVotesDescending(arr) {
    arr.sort((a, b) => {
        let fa = parseInt(a.total_votes),
            fb = parseInt(b.total_votes);
        // console.log(a);

        if (fa < fb) {
            return 1;
        }
        if (fa > fb) {
            return -1;
        }
        return 0;
    });
    return arr;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}
// const municipalities = JSON.parse(localStorage.getItem("municipality"));
// const positions = JSON.parse(localStorage.getItem("position"));

// const grouped = groupBy(
//     clusteredPrecinct.result,
//     (result) => result.municipality_name
// );
// console.log(grouped.get("BALABAC"));
console.log(clusteredPrecinct.result);
</script>
<template>
    <div class="bg-white px-6 lg:px-20 py-10 mx-auto relative" id="printDiv">
        <div v-for="(cp, index) in clusteredPrecinct.result" :key="index">
            <h3 class="font-semibold text-2xl">{{ cp.municipality }}</h3>
            <div
                class="text-sm"
                v-if="clusteredPrecinct.report_level == 'barangay'"
            >
                Barangay:
                <span class="font-semibold">{{ cp.barangay }}</span>
            </div>
            <div
                class="text-lg"
                v-if="clusteredPrecinct.report_level == 'district'"
            >
                District:
                <span class="font-semibold">{{
                    clusteredPrecinct.district
                }}</span>
            </div>
            <div class="text-sm">
                Registered Voters:
                <span class="font-semibold" v-if="cp.result">{{
                    numberWithCommas(cp.result.stats[0].reg_voters)
                }}</span>
            </div>
            <div class="text-sm">
                Total Turnout:
                <span class="font-semibold" v-if="cp.result">{{
                    numberWithCommas(cp.result.stats[0].total_turnout)
                }}</span>
            </div>
            <div class="text-sm">
                Turnout Percentage:
                <span class="font-semibold" v-if="cp.result"
                    >{{
                        percentage(
                            cp.result.stats[0].total_turnout,
                            cp.result.stats[0].reg_voters
                        )
                    }}%</span
                >
            </div>
            <div v-for="(b, index) in cp.result.turnouts" class="mt-6 lg:ml-8">
                <p class="text-xl font-semibold">{{ b.position }}</p>

                <table class="table table-compact mb-6">
                    <tr>
                        <th class="w-1/3">Candidate</th>
                        <th class="text-right">Votes</th>
                        <th class="text-right">Percentage</th>
                    </tr>
                    <tr
                        v-for="(c, i) in sortTotalVotesDescending(b.candidates)"
                        :key="i"
                        class="border-b"
                    >
                        <td>{{ c.candidate_name }}</td>
                        <td class="text-right">
                            {{ numberWithCommas(c.total_votes) }}
                        </td>
                        <td class="text-right">
                            {{
                                percentage(
                                    c.total_votes,
                                    b.position_total_votes
                                )
                            }}%
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold text-right pr-20 text-lg">
                            Total
                        </td>
                        <td class="font-semibold text-lg text-right">
                            {{ numberWithCommas(b.position_total_votes) }}
                        </td>
                        <td class="font-semibold text-lg text-right">100%</td>
                    </tr>
                </table>
                <div class="divider"></div>
            </div>
        </div>
    </div>
</template>
