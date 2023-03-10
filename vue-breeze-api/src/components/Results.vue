<script setup>
import { ref, onMounted } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
const today = new Date().toLocaleDateString("en-PH");
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

function printReport() {
    const maxPageHeight = 1024;
    let totalHeight = 0;
    const elements = document.querySelectorAll(".print-content");

    // console.log(printHeader);
    for (let i = 0; i < elements.length; i++) {
        let elementHeight = elements[i].offsetHeight;
        if (i == 0) {
            elementHeight -= 400;
        }
        // elements[i].classList.remove("break-page");
        // console.log(totalHeight);
        if (totalHeight > maxPageHeight) {
            elements[i].classList.add("break-page");
            // console.log(totalHeight);
            totalHeight = 0;
        }
        totalHeight += elementHeight;
    }
    setTimeout(function () {
        window.print();
    }, 500);
    return false;
}
const tableContainer = ref([]);
</script>
<template>
    <div id="printContent">
        <div class="printHeader hidden -mt-6">
            <div class="text-center">
                <span class="font-semibold text-xl"
                    >2022 National Election Results</span
                >
                <!-- <span class="float-right text-xs"
                    >Date Printed: {{ today }}</span
                > -->
            </div>
            <div class="mb-2">
                <table class="headerTable">
                    <tr>
                        <td class="w-[100px] font-semibold">DATE PRINTED:</td>
                        <td class="uppercase indent-0.5">
                            {{ today }}
                        </td>
                    </tr>
                    <tr>
                        <td class="w-[100px] font-semibold">REPORT LEVEL:</td>
                        <td class="uppercase indent-0.5">
                            {{ clusteredPrecinct.report_level }}
                        </td>
                    </tr>
                    <tr v-if="clusteredPrecinct.report_level == 'municipality'">
                        <td class="w-[100px] font-semibold">MUNICIPALITY:</td>
                        <td class="indent-0.5">
                            {{ clusteredPrecinct.municipality.join(", ") }}
                        </td>
                    </tr>
                    <tr>
                        <td class="w-[100px] font-semibold">POSITION:</td>
                        <td class="indent-0.5">
                            {{ clusteredPrecinct.position.join(", ") }}
                        </td>
                    </tr>
                </table>
            </div>
            <hr />
        </div>

        <div class="bg-white px-6 lg:px-20 py-10 mx-auto relative uppercase">
            <div
                v-for="(cp, index) in clusteredPrecinct.result"
                :key="index"
                ref="tableContainer"
                class="print-content"
            >
                <h3 class="font-semibold text-xl">{{ cp.municipality }}</h3>
                <div
                    class="text-sm"
                    v-if="clusteredPrecinct.report_level == 'barangay'"
                >
                    Barangay:
                    <span class="font-semibold">{{ cp.barangay }}</span>
                </div>
                <div
                    class="text-sm"
                    v-if="clusteredPrecinct.report_level == 'district'"
                >
                    District:
                    <span class="font-semibold">{{
                        clusteredPrecinct.district
                    }}</span>
                </div>
                <div class="text-sm">
                    Registered Voters:
                    <span class="font-semibold" v-if="cp.result.stats">{{
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
                <div class="text-sm">
                    JPM Members:
                    <span
                        class="font-semibold"
                        v-if="clusteredPrecinct.jpm_summary"
                        >{{
                            numberWithCommas(
                                clusteredPrecinct.jpm_summary.total_members
                            )
                        }}</span
                    >
                </div>
                <div
                    v-for="(b, index) in cp.result.turnouts"
                    class="mt-4 lg:ml-8"
                >
                    <p class="text-lg font-semibold">{{ b.position }}</p>

                    <table
                        class="table w-full table-compact mb-4 page-break-after-always contentTable"
                    >
                        <template
                            v-if="
                                clusteredPrecinct.report_level == 'province' &&
                                (b.position == 'CONGRESSMAN' ||
                                    b.position == 'BOARD MEMBER')
                            "
                        >
                            <template v-for="(d, i) in b.district">
                                <thead>
                                    <tr>
                                        <th colspan="4">District {{ d.id }}</th>
                                    </tr>

                                    <tr>
                                        <th colspan="4" class="py-0">
                                            <p class="text-xs font-normal">
                                                Registered Voters:
                                                {{
                                                    numberWithCommas(
                                                        d.reg_voters
                                                    )
                                                }}
                                            </p>
                                            <p class="text-xs font-normal">
                                                Total Turnout:
                                                {{
                                                    numberWithCommas(
                                                        d.total_turnout
                                                    )
                                                }}
                                            </p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="w-2 text-left">#</th>
                                        <th class="w-1/4 text-left">
                                            Candidate
                                        </th>
                                        <th class="text-right">Votes</th>
                                        <th class="text-right">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            c, i
                                        ) in sortTotalVotesDescending(
                                            d.candidates
                                        )"
                                    >
                                        <td>{{ i + 1 }}.</td>
                                        <td>{{ c.candidate_name }}</td>
                                        <td class="text-right">
                                            {{
                                                numberWithCommas(c.total_votes)
                                            }}
                                        </td>
                                        <td class="text-right">
                                            {{
                                                percentage(
                                                    c.total_votes,
                                                    d.position_total_votes
                                                )
                                            }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </template>
                        <template v-else>
                            <thead>
                                <tr>
                                    <th class="w-2 text-left">#</th>
                                    <th class="w-1/4 text-left">Candidate</th>
                                    <th class="text-right">Votes</th>
                                    <th class="text-right">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(c, i) in sortTotalVotesDescending(
                                        b.candidates
                                    )"
                                    :key="i"
                                    class="border-b"
                                >
                                    <td>{{ i + 1 }}.</td>
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
                            </tbody>

                            <tfoot>
                                <tr
                                    v-if="
                                        b.position != 'SENATOR' &&
                                        b.position != 'BOARD MEMBER' &&
                                        b.position != 'COUNCILOR' &&
                                        b.position != 'PARTY LIST'
                                    "
                                >
                                    <td
                                        class="font-semibold text-right pr-20 text-lg"
                                        colspan="2"
                                    >
                                        Total
                                    </td>
                                    <td
                                        class="font-semibold text-lg text-right"
                                    >
                                        {{
                                            numberWithCommas(
                                                b.position_total_votes
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="font-semibold text-lg text-right"
                                    >
                                        100%
                                    </td>
                                </tr>
                            </tfoot>
                        </template>
                    </table>
                    <div class="divider"></div>
                </div>
            </div>
        </div>
    </div>

    <button
        title="Print"
        @click="printReport"
        class="no-print fixed z-90 bottom-10 right-8 bg-red-400 w-20 h-20 rounded-full drop-shadow-lg flex justify-center items-center text-white text-4xl hover:bg-green-400 hover:drop-shadow-2xl hover:animate-bounce duration-300"
    >
        <mdicon name="printer" size="40" />
    </button>
</template>
<style>
@media print {
    @page {
        size: A4;
        margin: 16px 0;
    }
    .break-page {
        page-break-after: always;
    }
    .print-content {
        padding: 0;
        font-size: 12px;
    }
    .headerTable {
        border-collapse: collapse;
    }
    .headerTable td {
        vertical-align: top;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }
    .contentTable tr {
        page-break-inside: always;
        page-break-after: auto;
    }
    .contentTable th {
        padding: 0;
    }
    .contentTable td {
        vertical-align: top;
        font-size: 12px;
        padding: 1px 2px;
    }
    .printHeader {
        display: block;
    }
}
</style>
