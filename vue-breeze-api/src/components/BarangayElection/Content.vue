<script setup>
import HelloBar from "./HelloBar.vue";
import moment from "moment";
import { BskResultStore } from "../../store/bsk_result";
import { onMounted, computed, ref } from "vue";
const bskResult = BskResultStore();
onMounted(() => {
    bskResult.getResult();
});
const searchFilter = ref("");
const isProclaimedOnly = ref(false);
// const proclaimedFilter = ref(0);
const today = new Date().toLocaleDateString("en-PH");

const result = computed(() => {
    if (bskResult.result) {
        // const bskRes = bskResult.result;
        // if (searchFilter.value) {
        // console.log(bskResult.result);
        let bskRes = bskResult.result
            .filter((res) =>
                // console.log(res.candidate_nickname);
                // res.candidate_name.includes(searchFilter.value.toUpperCase()) ||
                // (res.candidate_nickname &&
                //     res.candidate_nickname.includes(
                //         searchFilter.value.toUpperCase()
                //     )) &&
                isProclaimedOnly.value &&
                res.candidate_position == "PUNONG BARANGAY"
                    ? res.candidate_rank == 1
                    : isProclaimedOnly.value &&
                      res.candidate_position == "BARANGAY KAGAWAD"
                    ? res.candidate_rank <= 7
                    : res
            )
            .filter(
                (res) =>
                    res.candidate_name.includes(
                        searchFilter.value.toUpperCase()
                    ) ||
                    (res.candidate_nickname &&
                        res.candidate_nickname.includes(
                            searchFilter.value.toUpperCase()
                        ))
            );

        return bskRes;
        // } else {
        //     return bskResult.result;
        // }
    }
});
</script>
<template>
    <div class="flex flex-col gap-6 p-2 md:p-7 relative">
        <div
            class="absolute bg-gradient-to-b from-gray-200 to-stone-100 opacity-75 inset-0 z-0 no-print"
        ></div>
        <div
            class="absolute inset-0 bg-[url(assets/img/grid.svg)] bg-center z-0 no-print"
        ></div>
        <hello-bar class="z-20 no-print" />
        <!-- <router-view class="z-20"></router-view> -->

        <div class="z-20 text-gray-500">
            <div class="printHeader hidden mt-8">
                <div class="text-center">
                    <span class="font-semibold text-2xl uppercase"
                        >2023 Barangay and Sangguniang Kabataan Election
                        Results</span
                    >
                    <!-- <span class="float-right text-xs"
                    >Date Printed: {{ today }}</span
                > -->
                </div>
                <div class="px-4 py-2 mx-auto">
                    <table class="headerTable">
                        <tr>
                            <!-- <td class="w-[120px] font-semibold">
                                DATE GENERATED:
                            </td>
                            <td class="uppercase indent-0.5">
                                {{ today }}
                            </td> -->
                        </tr>

                        <!-- <tr
                            v-if="
                                clusteredPrecinct.report_level == 'municipality'
                            "
                        >
                            <td class="w-[120px] font-semibold">
                                MUNICIPALITY:
                            </td>
                            <td class="indent-0.5">
                                {{ clusteredPrecinct.municipality.join(", ") }}
                            </td>
                        </tr> -->
                        <!-- <tr v-if="clusteredPrecinct.report_level != 'province'">
                            <td class="w-[120px] font-semibold">POSITION:</td>
                            <td class="indent-0.5">
                                {{ clusteredPrecinct.position.join(", ") }}
                            </td>
                        </tr> -->
                    </table>
                </div>
                <hr />
            </div>
            <table class="table table-compact w-full">
                <thead class="no-print">
                    <tr>
                        <td colspan="9">
                            <div class="flex gap-3">
                                <input
                                    class="input w-full max-w-xs font-normal uppercase rounded outline-none"
                                    type="text"
                                    v-model="searchFilter"
                                    placeholder="Search for name or nickname"
                                />

                                <div class="form-control mt-2">
                                    <label class="label cursor-pointer">
                                        <span
                                            class="label-text font-semibold text-gray-500"
                                            >Proclaimed</span
                                        >
                                        <input
                                            v-model="isProclaimedOnly"
                                            type="checkbox"
                                            class="checkbox checkbox-sm ml-2"
                                        />
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="text-xs">#</th>
                        <th>Municipality</th>
                        <th>Barangay</th>
                        <th>Position</th>
                        <th>Name</th>
                        <th>Nickname</th>
                        <th>Votes</th>
                        <th>Rank</th>
                        <th>Proclaimed</th>
                    </tr>
                </thead>

                <tbody v-if="bskResult.result">
                    <tr
                        v-for="(res, i) in result"
                        :class="
                            res.candidate_position == 'PUNONG BARANGAY' &&
                            res.candidate_rank == 1
                                ? 'font-semibold'
                                : res.candidate_position ==
                                      'BARANGAY KAGAWAD' &&
                                  res.candidate_rank <= 7
                                ? 'font-semibold'
                                : ''
                        "
                    >
                        <td class="text-xs text-gray-400">
                            {{ i + 1 }}
                        </td>
                        <td class="text-xs">{{ res.municipality_name }}</td>
                        <td class="whitespace-normal max-w-[11rem] text-xs">
                            {{ res.barangay_name }}
                        </td>
                        <td class="text-xs">{{ res.candidate_position }}</td>
                        <td class="max-w-[13rem] whitespace-normal text-xs">
                            {{ res.candidate_name }}
                        </td>
                        <td class="max-w-[8px] text-xs">
                            {{ res.candidate_nickname }}
                        </td>
                        <td class="text-xs">{{ res.total_votes }}</td>
                        <td class="text-xs">{{ res.candidate_rank }}</td>
                        <td class="text-xs">
                            {{
                                res.proclamation_date
                                    ? moment(res.proclamation_date).format("L")
                                    : ""
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- <pre>{{ bskResult.result }}</pre> -->
        </div>
    </div>
</template>
<style>
@media print {
    @page {
        margin: 16px 0;
    }
    .break-page {
        page-break-after: always;
    }
    .print-content {
        padding: 0;
        font-size: 12px;
    }
    .print-content * {
        color: #333 !important;
    }

    .print-content .hidden {
        display: block;
    }
    .headerTable {
        border-collapse: collapse;
    }
    .headerTable td {
        vertical-align: top;
        padding: 0;
        margin: 0;
        font-size: 12px;
    }
    .contentTable {
        padding: 0 12px;
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
        font-size: 14px;
        padding: 1px 2px;
    }
    .contentTable .tfooter td {
        border-top: 1px solid #eee;
    }
    .printHeader {
        display: block;
    }
}
</style>
