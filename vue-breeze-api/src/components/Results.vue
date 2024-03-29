<script setup>
import { ref, onMounted } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
const today = new Date().toLocaleDateString("en-PH");
const toggleJPM = ref(false);
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
document.addEventListener("keydown", function (event) {
    if (event.key === "j") {
        toggleJPM.value = !toggleJPM.value;
    }
});
</script>
<template>
    <label class="swap swap-rotate">
        <!-- this hidden checkbox controls the state -->
        <input
            type="checkbox"
            v-model="toggleJPM"
            @click="toggleJPM = !toggleJPM"
        />

        <!-- sun icon -->
        <mdicon name="eye-outline" class="swap-on text-gray-50" />

        <!-- moon icon -->
        <mdicon name="eye-off-outline" class="swap-off text-gray-50" />
    </label>
    <div id="printContent" v-if="clusteredPrecinct">
        <div class="printHeader hidden -mt-2">
            <div class="text-center">
                <span class="font-semibold text-2xl uppercase"
                    >2022 National and Local Election Results</span
                >
                <!-- <span class="float-right text-xs"
                    >Date Printed: {{ today }}</span
                > -->
            </div>
            <div class="px-4 py-2 mx-auto">
                <table class="headerTable">
                    <tr>
                        <td class="w-[120px] font-semibold">DATE GENERATED:</td>
                        <td class="uppercase indent-0.5">
                            {{ today }}
                        </td>
                    </tr>
                    <tr>
                        <td class="w-[120px] font-semibold">REPORT LEVEL:</td>
                        <td class="uppercase indent-0.5">
                            {{ clusteredPrecinct.report_level }}
                        </td>
                    </tr>
                    <tr v-if="clusteredPrecinct.report_level == 'municipality'">
                        <td class="w-[120px] font-semibold">MUNICIPALITY:</td>
                        <td class="indent-0.5">
                            {{ clusteredPrecinct.municipality.join(", ") }}
                        </td>
                    </tr>
                    <tr v-if="clusteredPrecinct.report_level != 'province'">
                        <td class="w-[120px] font-semibold">POSITION:</td>
                        <td class="indent-0.5">
                            {{ clusteredPrecinct.position.join(", ") }}
                        </td>
                    </tr>
                </table>
            </div>
            <hr />
        </div>
        <div class="no-print py-4"></div>
        <div class="bg-white px-6 lg:px-20 py-2 mx-auto relative uppercase">
            <div
                v-if="clusteredPrecinct.report_level == 'barangay'"
                class="mb-2"
            >
                <div v-if="!/CITY/i.test(clusteredPrecinct.municipality)">
                    <div
                        class="py-0 font-semibold text-gray-600 text-xl md:text-2xl lg:text-3xl whitespace-normal md:whitespace-nowrap"
                        colspan="2"
                    >
                        Municipality of {{ clusteredPrecinct.municipality }}
                    </div>
                </div>
                <div v-else>
                    <div
                        class="py-0 font-semibold text-gray-600 text-xl md:text-2xl lg:text-3xl"
                        colspan="2"
                    >
                        {{ clusteredPrecinct.municipality }}
                    </div>
                </div>
            </div>
            <div
                v-for="(cp, index) in clusteredPrecinct.result"
                :key="index"
                ref="tableContainer"
                class="print-content"
            >
                <div>
                    <table class="table table-compact table-zebra">
                        <tbody>
                            <template
                                v-if="
                                    clusteredPrecinct.report_level == 'province'
                                "
                            >
                                <tr class="">
                                    <td
                                        class="py-0 w-[140px] text-xl md:text-2xl lg:text-3xl text-gray-600 font-semibold whitespace-normal md:whitespace-nowrap"
                                        colspan="2"
                                    >
                                        Province of Palawan
                                    </td>
                                </tr>
                            </template>
                            <template
                                v-if="
                                    clusteredPrecinct.report_level ==
                                    'municipality'
                                "
                            >
                                <tr v-if="!/CITY/i.test(cp.municipality)">
                                    <td
                                        class="py-0 font-semibold text-gray-600 text-xl md:text-2xl lg:text-3xl whitespace-normal md:whitespace-nowrap"
                                        colspan="2"
                                    >
                                        Municipality of {{ cp.municipality }}
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td
                                        class="py-0 font-semibold text-gray-600 text-xl md:text-2xl lg:text-3xl"
                                        colspan="2"
                                    >
                                        {{ cp.municipality }}
                                    </td>
                                </tr>
                            </template>
                            <template
                                v-if="
                                    clusteredPrecinct.report_level == 'barangay'
                                "
                            >
                                <!-- <tr v-if="!/CITY/i.test(cp.municipality)">
                                    <td
                                        class="py-0 font-semibold text-gray-600 text-[20px] whitespace-nowrap"
                                        colspan="2"
                                    >
                                        Municipality of {{ cp.municipality }}
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td
                                        class="py-0 font-semibold text-gray-600 text-[20px] whitespace-nowrap"
                                        colspan="2"
                                    >
                                        {{ cp.municipality }}
                                    </td>
                                </tr> -->
                                <tr>
                                    <td
                                        class="py-0 w-[140px] text-gray-600 text-sm md:text-base font-semibold"
                                    >
                                        Barangay:
                                    </td>
                                    <td
                                        class="py-0 px-2 font-semibold text-sm md:text-base text-gray-600"
                                    >
                                        {{ cp.barangay }}
                                    </td>
                                </tr>
                            </template>
                            <template
                                v-if="
                                    clusteredPrecinct.report_level == 'district'
                                "
                            >
                                <tr>
                                    <td
                                        class="py-0 w-[140px] text-[20px] text-gray-600 font-semibold whitespace-nowrap"
                                        colspan="2"
                                    >
                                        <span
                                            v-if="
                                                clusteredPrecinct.district == 1
                                            "
                                            >1st Congressional district of
                                            Palawan</span
                                        >
                                        <span
                                            v-if="
                                                clusteredPrecinct.district == 2
                                            "
                                            >2nd Congressional district of
                                            Palawan</span
                                        >
                                        <span
                                            v-if="
                                                clusteredPrecinct.district == 3
                                            "
                                            >3rd Congressional district of
                                            Palawan</span
                                        >
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td
                                    class="py-0 w-[140px] text-gray-600 text-sm md:text-base font-semibold"
                                >
                                    Registered Voters:
                                </td>
                                <td
                                    class="py-0 px-2 font-semibold text-gray-600"
                                >
                                    {{
                                        numberWithCommas(
                                            cp.result.stats[0].reg_voters
                                        )
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="py-0 w-[140px] text-gray-600 text-sm md:text-base font-semibold"
                                >
                                    Total Turnout:
                                </td>
                                <td
                                    class="py-0 px-2 font-semibold text-gray-600"
                                >
                                    {{
                                        numberWithCommas(
                                            cp.result.stats[0].total_turnout
                                        )
                                    }}
                                    <span
                                        class="font-normal text-sm md:text-base"
                                        >&nbsp;({{
                                            percentage(
                                                cp.result.stats[0]
                                                    .total_turnout,
                                                cp.result.stats[0].reg_voters
                                            )
                                        }}%)</span
                                    >
                                </td>
                            </tr>
                            <tr v-if="toggleJPM">
                                <td
                                    class="py-0 w-[140px] text-gray-600 text-sm md:text-base font-semibold"
                                >
                                    JPM Members:
                                </td>
                                <td
                                    class="py-0 px-2 font-semibold text-gray-600"
                                >
                                    {{
                                        numberWithCommas(
                                            cp.jpm_members.total_members
                                        )
                                    }}
                                    <span
                                        class="font-normal text-sm md:text-base"
                                        >&nbsp;({{
                                            percentage(
                                                cp.jpm_members.total_members,
                                                cp.result.stats[0].reg_voters
                                            )
                                        }}%)</span
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    v-for="(b, index) in cp.result.turnouts"
                    class="mt-2 lg:ml-4 contentTable"
                >
                    <table class="table w-full table-compact mb-2">
                        <thead>
                            <tr>
                                <th
                                    colspan="5"
                                    class="bg-gray-100 text-gray-600"
                                >
                                    <p class="text-lg md:text-xl font-semibold">
                                        {{ b.position }}
                                    </p>
                                </th>
                            </tr>
                        </thead>
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
                                        <th
                                            colspan="5"
                                            class="bg-gray-50 text-gray-600 pl-2"
                                        >
                                            District {{ d.id }}
                                        </th>
                                    </tr>

                                    <tr>
                                        <th
                                            colspan="5"
                                            class="py-0 bg-slate-50 text-gray-600 pl-4"
                                        >
                                            <div class="flex align-bottom">
                                                <p class="w-[120px]">
                                                    <span
                                                        class="font-semibold text-[11px]"
                                                        >Registered
                                                        Voters:</span
                                                    >
                                                </p>
                                                <p
                                                    class="font-semibold text-[14px]"
                                                >
                                                    {{
                                                        numberWithCommas(
                                                            d.reg_voters
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div class="flex align-bottom">
                                                <p class="w-[120px]">
                                                    <span
                                                        class="font-semibold text-[11px]"
                                                    >
                                                        Total Turnout:</span
                                                    >
                                                </p>
                                                <p
                                                    class="font-semibold text-[14px]"
                                                >
                                                    {{
                                                        numberWithCommas(
                                                            d.total_turnout
                                                        )
                                                    }}
                                                    <span
                                                        class="font-normal text-[12px] hidden md:inline-block"
                                                        >({{
                                                            percentage(
                                                                d.total_turnout,
                                                                d.reg_voters
                                                            )
                                                        }}%)</span
                                                    >
                                                </p>
                                            </div>
                                            <div
                                                class="flex align-bottom"
                                                v-if="toggleJPM"
                                            >
                                                <p class="w-[120px]">
                                                    <span
                                                        class="font-semibold text-[11px]"
                                                    >
                                                        JPM Members:</span
                                                    >
                                                </p>
                                                <p
                                                    class="font-semibold text-[14px]"
                                                    v-if="d.jpm_members"
                                                >
                                                    {{
                                                        numberWithCommas(
                                                            d.jpm_members
                                                                .total_members
                                                        )
                                                    }}
                                                    <span
                                                        class="font-normal text-[12px]"
                                                        >({{
                                                            percentage(
                                                                d.jpm_members
                                                                    .total_members,
                                                                d.reg_voters
                                                            )
                                                        }}%)</span
                                                    >
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th
                                            class="w-2 text-left text-xs bg-transparent text-gray-600"
                                        >
                                            #
                                        </th>
                                        <th
                                            class="w-1/4 text-left text-xs bg-transparent text-gray-600"
                                        >
                                            Candidate
                                        </th>
                                        <th
                                            class="text-right text-xs bg-transparent text-gray-600"
                                        >
                                            Votes
                                        </th>
                                        <th
                                            class="text-right text-xs bg-transparent text-gray-600"
                                        >
                                            Votes Difference
                                        </th>
                                        <th
                                            class="text-right text-xs bg-transparent text-gray-600 hidden md:block"
                                        >
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            c, i
                                        ) in sortTotalVotesDescending(
                                            d.candidates
                                        )"
                                        :class="
                                            b.position == 'CONGRESSMAN' &&
                                            i == 0
                                                ? 'font-bold'
                                                : ''
                                        "
                                    >
                                        <td
                                            class="p-1 md:p-2 text-[10px] md:text-xs text-gray-500 mb-4"
                                        >
                                            {{ i + 1 }}.
                                        </td>
                                        <td
                                            class="font-mono text-[11px] md:text-sm md:p-2"
                                        >
                                            {{ c.candidate_name }}
                                        </td>
                                        <td
                                            class="text-right text-[11px] md:text-sm md:p-2"
                                        >
                                            {{
                                                numberWithCommas(c.total_votes)
                                            }}
                                            <span
                                                class="font-normal text-[10px] md:hidden text-gray-500"
                                                >({{
                                                    percentage(
                                                        c.total_votes,
                                                        d.position_total_votes
                                                    )
                                                }}%)</span
                                            >
                                        </td>
                                        <td
                                            class="text-right text-[11px] md:text-sm md:p-2"
                                        >
                                            <span
                                                v-if="
                                                    b.position == 'CONGRESSMAN'
                                                "
                                            >
                                                {{
                                                    numberWithCommas(
                                                        sortTotalVotesDescending(
                                                            d.candidates
                                                        )[0].total_votes -
                                                            c.total_votes
                                                    )
                                                }}
                                            </span>
                                            <span v-else>
                                                {{
                                                    numberWithCommas(
                                                        sortTotalVotesDescending(
                                                            d.candidates
                                                        )[i == 0 ? i : i - 1]
                                                            .total_votes -
                                                            c.total_votes
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="text-right text-[11px] md:text-sm md:p-2 hidden md:block"
                                        >
                                            {{
                                                percentage(
                                                    c.total_votes,
                                                    d.position_total_votes
                                                )
                                            }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="py-2"></td>
                                    </tr>
                                </tbody>
                            </template>
                        </template>
                        <template v-else>
                            <thead>
                                <tr>
                                    <th
                                        class="w-2 text-left text-xs bg-transparent text-gray-600"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="w-1/4 text-left text-xs md:text-lg bg-transparent text-gray-600"
                                    >
                                        Candidate
                                    </th>
                                    <th
                                        class="text-right text-xs md:text-lg bg-transparent text-gray-600"
                                    >
                                        Votes
                                    </th>
                                    <th
                                        class="text-right text-xs md:text-lg bg-transparent text-gray-600"
                                    >
                                        Votes Difference
                                    </th>
                                    <th
                                        class="text-right text-xs md:text-lg bg-transparent text-gray-600 hidden md:block"
                                    >
                                        Percentage
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(c, i) in sortTotalVotesDescending(
                                        b.candidates
                                    )"
                                    :key="i"
                                    :class="
                                        b.position !== 'SENATOR' &&
                                        b.position !== 'BOARD MEMBER' &&
                                        b.position !== 'PARTY LIST' &&
                                        i == 0
                                            ? 'font-bold'
                                            : b.position == 'SENATOR' && i < 12
                                            ? 'font-bold'
                                            : ''
                                    "
                                >
                                    <td
                                        class="p-1 md:p-2 text-[10px] md:text-sm lg:text-base text-gray-500 mb-4"
                                    >
                                        {{ i + 1 }}.
                                    </td>
                                    <td
                                        class="font-mono text-[11px] md:text-sm lg:text-xl md:p-2 text-gray-600"
                                    >
                                        {{ c.candidate_name }}
                                    </td>
                                    <td
                                        class="text-right text-[11px] md:text-base lg:text-lg md:p-2"
                                    >
                                        {{ numberWithCommas(c.total_votes) }}
                                        <span
                                            class="font-normal text-[10px] md:hidden no-print text-gray-500"
                                            >({{
                                                percentage(
                                                    c.total_votes,
                                                    b.position_total_votes
                                                )
                                            }}%)</span
                                        >
                                    </td>
                                    <td
                                        class="text-right text-[11px] md:text-base lg:text-lg md:p-2"
                                    >
                                        <span
                                            v-if="
                                                b.position == 'PRESIDENT' ||
                                                b.position == 'V-PRESIDENT' ||
                                                b.position == 'CONGRESSMAN' ||
                                                b.position == 'GOVERNOR' ||
                                                b.position == 'VICE-GOVERNOR'
                                            "
                                        >
                                            {{
                                                numberWithCommas(
                                                    sortTotalVotesDescending(
                                                        b.candidates
                                                    )[0].total_votes -
                                                        c.total_votes
                                                )
                                            }}
                                        </span>
                                        <span v-else>{{
                                            numberWithCommas(
                                                sortTotalVotesDescending(
                                                    b.candidates
                                                )[i == 0 ? i : i - 1]
                                                    .total_votes - c.total_votes
                                            )
                                        }}</span>
                                    </td>
                                    <td
                                        class="text-right text-[11px] md:text-base lg:text-lg md:p-2 hidden md:block"
                                    >
                                        {{
                                            percentage(
                                                c.total_votes,
                                                b.position_total_votes
                                            )
                                        }}%
                                    </td>
                                </tr>
                            </tbody>

                            <tbody class="tfooter">
                                <tr
                                    v-if="
                                        b.position != 'SENATOR' &&
                                        b.position != 'BOARD MEMBER' &&
                                        b.position != 'COUNCILOR' &&
                                        b.position != 'PARTY LIST'
                                    "
                                >
                                    <td
                                        class="font-semibold text-right bg-gray-100 text-gray-600 text-xs md:text-base lg:text-lg"
                                        colspan="2"
                                    >
                                        Total
                                    </td>
                                    <td
                                        class="font-semibold text-right bg-gray-100 text-gray-600 text-xs md:text-base lg:text-lg"
                                    >
                                        {{
                                            numberWithCommas(
                                                b.position_total_votes
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="font-semibold text-right bg-gray-100 text-gray-600 text-xs md:text-base lg:text-lg"
                                    ></td>
                                    <td
                                        class="font-semibold text-right bg-gray-100 text-gray-600 text-xs md:text-base lg:text-lg hidden md:block"
                                    >
                                        100%
                                    </td>
                                </tr>
                            </tbody>
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
        class="no-print fixed z-[1000] bottom-6 md:bottom-10 right-8 bg-red-400 md:w-16 md:h-16 rounded-full drop-shadow-lg flex justify-center items-center text-white text-4xl hover:bg-green-400 hover:drop-shadow-2xl hover:animate-bounce duration-300 w-12 h-12"
    >
        <mdicon name="printer" size="36" />
    </button>
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
