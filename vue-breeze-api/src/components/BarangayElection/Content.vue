<script setup>
import HelloBar from "./HelloBar.vue";
import moment from "moment";
import { BskResultStore } from "../../store/bsk_result";
import { onMounted, computed, ref } from "vue";
const bskResult = BskResultStore();
onMounted(() => {
    if (bskResult.result) {
        bskResult.getResult();
    }
});
const searchFilter = ref("");
const isProclaimedOnly = ref(false);
const today = new Date().toLocaleDateString("en-PH");

const results = computed(() => {
    try {
        if (Array.isArray(bskResult.result))
            return bskResult.result.map((group) => {
                const barangays = group.barangays
                    .map((barangay) => {
                        const filteredPositions = barangay.positions
                            .map((pos) => {
                                const filteredResult = pos.result
                                    .filter(
                                        (res) =>
                                            res.candidate_name.includes(
                                                searchFilter.value.toUpperCase()
                                            ) ||
                                            (res.candidate_nickname &&
                                                res.candidate_nickname.includes(
                                                    searchFilter.value.toUpperCase()
                                                ))
                                    )
                                    .filter((res) =>
                                        isProclaimedOnly.value &&
                                        res.candidate_position ==
                                            "PUNONG BARANGAY"
                                            ? res.candidate_rank == 1
                                            : isProclaimedOnly.value &&
                                              res.candidate_position ==
                                                  "BARANGAY KAGAWAD"
                                            ? res.candidate_rank <= 7
                                            : res
                                    );
                                if (filteredResult.length) {
                                    return {
                                        position: pos.position_name,
                                        result: filteredResult,
                                    };
                                }
                            })
                            .filter(
                                (position) =>
                                    position !== null &&
                                    position !== undefined &&
                                    position !== 0
                            );
                        if (filteredPositions.length)
                            return {
                                barangay_name: barangay.barangay_name,
                                positions: filteredPositions,
                            };
                    })
                    .filter(
                        (barangay) =>
                            barangay !== null &&
                            barangay !== undefined &&
                            barangay !== 0
                    );

                return {
                    municipality_name: group.municipality_name,
                    barangays: barangays,
                };
            });
    } catch (err) {
        console.log(err);
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
            <div class="printHeader hidden mt-4">
                <div class="text-center">
                    <span class="font-semibold text-2xl uppercase"
                        >2023 Barangay and Sangguniang Kabataan Election
                        Results</span
                    >
                </div>
                <div class="px-4 py-2 mx-auto">
                    <table class="headerTable">
                        <tr>
                            <td class="w-[120px] font-semibold">
                                DATE GENERATED:
                            </td>
                            <td class="uppercase indent-0.5">
                                {{ today }}
                            </td>
                        </tr>

                        <tr v-if="bskResult.report_level == 'municipality'">
                            <td class="w-[120px] font-semibold">
                                MUNICIPALITY:
                            </td>
                            <td class="indent-0.5">
                                {{ bskResult.municipality.join(", ") }}
                            </td>
                        </tr>
                        <tr v-if="bskResult.report_level != 'province'">
                            <td class="w-[120px] font-semibold">POSITION:</td>
                            <td class="indent-0.5">
                                {{ bskResult.position.join(", ") }}
                            </td>
                        </tr>
                    </table>
                </div>
                <hr />
            </div>

            <div class="rounded bg-gray-50">
                <div class="flex gap-3 py-8 px-4 no-print">
                    <input
                        class="input w-full max-w-xs font-normal uppercase rounded border-gray-400"
                        type="text"
                        v-model="searchFilter"
                        name="search-filter"
                        placeholder="Search for name or nickname"
                    />

                    <div class="form-control mt-2">
                        <label class="label cursor-pointer">
                            <span class="label-text font-semibold text-gray-500"
                                >Proclaimed</span
                            >
                            <input
                                v-model="isProclaimedOnly"
                                type="checkbox"
                                class="checkbox checkbox-sm ml-2"
                                name="is-proclaimed"
                            />
                        </label>
                    </div>
                </div>
                <!-- <pre>{{ results }}</pre> -->

                <div v-for="(result, idxres) in results" :key="idxres">
                    <div v-for="(bgy, i) in result.barangays">
                        <div class="px-6 py-4">
                            <p class="text-xl font-semibold">
                                {{
                                    result.municipality_name ==
                                    "PUERTO PRINCESA CITY"
                                        ? result.municipality_name
                                        : "MUNICIPALITY OF " +
                                          result.municipality_name
                                }}
                            </p>
                            <span class="text-lg"
                                >BGY. {{ bgy.barangay_name }}
                            </span>
                            <div class="px-4 overflow-x-auto">
                                <table
                                    class="table table-compact w-full shadow mb-8 uppercase"
                                    v-for="(pos, idxp) in bgy.positions"
                                    :key="'pos' + idxp"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                colspan="6"
                                                class="bg-gray-50 border-b-0 border-t-2"
                                            >
                                                {{ pos.position }} -
                                                {{ bgy.barangay_name }}
                                            </th>
                                        </tr>
                                        <tr class="border-b">
                                            <th class="text-xs bg-gray-50">
                                                Rank
                                            </th>

                                            <th class="bg-gray-50">
                                                <span class="font-semibold"
                                                    >Name</span
                                                >
                                            </th>
                                            <th class="bg-gray-50">
                                                <span class="font-semibold"
                                                    >Nickname</span
                                                >
                                            </th>
                                            <th class="bg-gray-50">
                                                <span class="font-semibold"
                                                    >Votes</span
                                                >
                                            </th>
                                            <!-- <th>
                                            <span class="font-semibold"
                                                >Rank</span
                                            >
                                        </th> -->
                                            <th class="bg-gray-50">
                                                <span class="font-semibold"
                                                    >Date Proclaimed</span
                                                >
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(res, idxr) in pos.result"
                                            :key="'bskres' + idxr"
                                            v-if="
                                                Array.isArray(pos.result) &&
                                                pos.result.length > 0
                                            "
                                        >
                                            <tr
                                                :class="
                                                    res.candidate_position ==
                                                        'PUNONG BARANGAY' &&
                                                    res.candidate_rank == 1
                                                        ? 'font-semibold'
                                                        : res.candidate_position ==
                                                              'BARANGAY KAGAWAD' &&
                                                          res.candidate_rank <=
                                                              7
                                                        ? 'font-semibold'
                                                        : ''
                                                "
                                            >
                                                <td class="text-xs">
                                                    {{ res.candidate_rank }}
                                                </td>

                                                <td class="text-xs">
                                                    {{ res.candidate_name }}
                                                </td>
                                                <td class="text-xs">
                                                    {{ res.candidate_nickname }}
                                                </td>
                                                <td class="text-xs">
                                                    {{ res.total_votes }}
                                                </td>

                                                <td class="text-xs">
                                                    {{
                                                        res.proclamation_date
                                                            ? moment(
                                                                  res.proclamation_date
                                                              ).format("L")
                                                            : ""
                                                    }}
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
@media print {
    @page {
        margin: 16px 0;
    }
    * {
        box-shadow: none !important;
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
