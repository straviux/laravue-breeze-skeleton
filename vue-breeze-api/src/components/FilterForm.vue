<script setup>
import { ref, computed, onMounted } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
const model = ref({
    district: 1,
    municipalities: [],
    barangays: [],
    positions: [],
    report_level: "province",
});
const allCitySelected = ref(false);
const allBarangaySelected = ref(false);
const allPositionSelected = ref(true);
const congressionalDistricts = [
    {
        id: 1,
        name: "1st",
        municipalities: [
            "AGUTAYA",
            "ARACELI",
            "BUSUANGA",
            "CAGAYANCILLO",
            "CORON",
            "CULION",
            "CUYO",
            "DUMARAN",
            "EL NIDO (BACUIT)",
            "KALAYAAN",
            "LINAPACAN",
            "MAGSAYSAY",
            "ROXAS",
            "SAN VICENTE",
            "TAYTAY",
        ],
    },
    {
        id: 2,
        name: "2nd",
        municipalities: [
            "BALABAC",
            "BATARAZA",
            "BROOKE'S POINT",
            "NARRA",
            "QUEZON",
            "RIZAL (MARCOS)",
            "SOFRONIO ESPAÑOLA",
        ],
    },
    {
        id: 3,
        name: "3rd",
        municipalities: ["ABORLAN", "PUERTO PRINCESA CITY"],
    },
    // ...and so on
];

const positions = [
    "PRESIDENT",
    "V-PRESIDENT",
    "CONGRESSMAN",
    "GOVERNOR",
    "VICE-GOVERNOR",
    "BOARD MEMBER",
    "MAYOR",
    "VICE-MAYOR",
    "COUNCILOR",
    "SENATOR",
    "PARTY LIST",
];

const filteredPositions = computed(() => {
    // let newPositions = positions;

    let valuesToRemove = [];
    if (
        model.value.report_level === "district" ||
        model.value.report_level === "province"
    ) {
        valuesToRemove =
            clusteredPrecinct.province == "PALAWAN"
                ? ["MAYOR", "VICE-MAYOR", "COUNCILOR"]
                : [];
        return positions.filter((pos) => !valuesToRemove.includes(pos));
    } else {
        valuesToRemove = ["GOVERNOR", "VICE-GOVERNOR", "BOARD MEMBER"];
        if (
            model.value.municipalities == "PUERTO PRINCESA CITY" ||
            model.value.municipalities.includes("PUERTO PRINCESA CITY")
        ) {
            // console.log(model.value.municipalities);
            return positions.filter((pos) => !valuesToRemove.includes(pos));
        } else {
            return positions;
        }
    }
});

const municipalities = computed(() => {
    if (clusteredPrecinct.province == "PALAWAN") {
        const district = congressionalDistricts.find(
            (d) => d.id == model.value.district
        );
        return district ? district.municipalities : [];
    } else {
        // let m = clusteredPrecinct.municipality;
        if (Array.isArray(clusteredPrecinct.municipality)) {
            let m = clusteredPrecinct.municipality.map((m) => {
                return m.municipality_name ?? m;
            });
            return m;
        } else {
            let m = [clusteredPrecinct.municipality];
            return m;
        }
    }
});

function resetForm() {
    model.value.municipalities = [];
    model.value.barangays = [];
    // model.value.district = null;
    // model.value.positions = filteredPositions.value;
    allCitySelected.value = false;
    setLimitedAccessDefault();
}
function resetButton() {
    model.value.municipalities = [];
    model.value.barangays = [];
    // model.value.district = null;
    model.value.positions = filteredPositions.value;
    allCitySelected.value = false;
    setLimitedAccessDefault();
}

function toggleAllCity() {
    if (allCitySelected.value) {
        model.value.municipalities = municipalities.value.flatMap(
            (city) => city
        );
    } else {
        model.value.municipalities = [];
    }
}

function toggleAllBarangay() {
    if (allBarangaySelected.value) {
        model.value.barangays = clusteredPrecinct.barangay.flatMap(
            (b) => b.barangay_name
        );
    } else {
        model.value.barangays = [];
    }
}

function toggleAllPosition() {
    if (allPositionSelected.value) {
        model.value.positions = filteredPositions.value.flatMap((p) => p);
    } else {
        model.value.positions = [];
    }
}

function toggleMunicipality() {
    allCitySelected.value = false;
    model.value.barangays = [];
    // model.value.positions = filteredPositions.value.flatMap((p) => p);

    if (model.value.report_level == "barangay") {
        clusteredPrecinct.getBarangay(model.value.municipalities);
    }
}

function removePosition(pos) {
    model.value.positions = model.value.positions.filter((p) => {
        return p != pos;
    });
}

function removeMunicipality(municipality) {
    if (model.value.report_level == "barangay") {
        model.value.municipalities = [];
    } else {
        model.value.municipalities = model.value.municipalities.filter((m) => {
            return m != municipality;
        });
    }
}

function removeBarangay(bgy) {
    model.value.barangays = model.value.barangays.filter((b) => {
        return b != bgy;
    });
}

function closeDropdownOnClick(e) {
    let targetEl = e.currentTarget;
    // console.log(targetEl);
    if (targetEl && targetEl.matches(":focus")) {
        setTimeout(function () {
            targetEl.blur();
        }, 0);
    }
    // e.target.parentElement.classList.toggle("dropdown-open");
    // document.activeElement.blur();
}

function resetPosition() {
    allPositionSelected.value = false;
    // model.value.municipalities = [];
}

function setLimitedAccessDefault() {
    if (clusteredPrecinct.has_limited_access) {
        if (model.value.report_level !== "barangay") {
            allCitySelected.value = true;
            toggleAllCity();
        } else {
            model.value.municipalities = clusteredPrecinct.municipality;
            clusteredPrecinct.getBarangay(model.value.municipalities);
        }
    }
}
onMounted(() => {
    model.value.positions = filteredPositions.value.flatMap((p) => p);
    if (clusteredPrecinct.has_limited_access) {
        model.value.report_level = "municipality";
        setLimitedAccessDefault();
    }

    // const dropdownContent = document.querySelectorAll(".oneclick-dropdown>li");
    // dropdownContent.forEach((element) => {
    //     element.addEventListener("click", () => {
    //         document.activeElement.blur();
    //     });
    // });
    // console.log(clusteredPrecinct.province);
    // clusteredPrecinct.getMunicipality(clusteredPrecinct.province);
});
</script>
<template>
    <div class="relative">
        <!-- <button
            class="text-sm px-2 text-gray-500 rounded-full flex gap-1 drop-shadow-lg"
        >
            <mdicon name="chart-box-outline" width="15px" />
            <span class="font-bold"
                >Generated {{ clusteredPrecinct.visit_count }}x</span
            >
        </button> -->
        <form @submit.prevent="">
            <p class="mx-4 mt-4 px-1 text-[14px] font-semibold text-gray-600">
                Report level:
            </p>

            <!-- Report Options -->
            <div class="grid grid-cols-2 border rounded mx-4 my-2 px-1">
                <div
                    class="py-2"
                    v-if="clusteredPrecinct.province == 'PALAWAN'"
                >
                    <label
                        class="flex p-2 cursor-pointer gap-1 whitespace-nowrap"
                    >
                        <input
                            class="my-auto radio radio-xs"
                            type="radio"
                            name="report_level"
                            value="province"
                            v-model="model.report_level"
                            @change="resetForm"
                        />
                        <span class="label-text uppercase text-xs"
                            >Province (Overall)</span
                        >
                    </label>
                </div>
                <div
                    class="py-2"
                    v-if="clusteredPrecinct.province == 'PALAWAN'"
                >
                    <label class="flex p-2 cursor-pointer gap-1">
                        <input
                            class="my-auto radio radio-xs"
                            type="radio"
                            name="report_level"
                            value="district"
                            v-model="model.report_level"
                            @change="resetForm"
                        />
                        <span class="label-text uppercase text-xs"
                            >District</span
                        >
                    </label>
                </div>
                <div class="py-2">
                    <label class="flex p-2 cursor-pointer gap-1">
                        <input
                            class="my-auto radio radio-xs"
                            type="radio"
                            name="report_level"
                            value="municipality"
                            ref="municipalityRadioBtn"
                            v-model="model.report_level"
                            @change="resetForm"
                        />
                        <span class="label-text uppercase text-xs"
                            >Municipality</span
                        >
                    </label>
                </div>
                <div class="py-2">
                    <label class="flex p-2 cursor-pointer gap-1">
                        <input
                            class="my-auto radio radio-xs"
                            type="radio"
                            name="report_level"
                            value="barangay"
                            v-model="model.report_level"
                            @change="resetForm"
                        />
                        <span class="label-text uppercase text-xs"
                            >Barangay</span
                        >
                    </label>
                </div>
            </div>
            <!-- End Report Level -->
            <div class="divider"></div>
            <!-- District -->
            <div
                class="flex border rounded m-4 px-1"
                v-if="
                    model.report_level !== 'province' &&
                    clusteredPrecinct.province == 'PALAWAN'
                "
            >
                <div
                    class="py-3 my-auto px-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    District
                </div>

                <ul tabindex="0" class="flex p-2 gap-1 w-full max-h-[300px]">
                    <li v-for="d in congressionalDistricts">
                        <label class="flex p-2 cursor-pointer ml-3 gap-1">
                            <input
                                class="my-auto radio radio-xs"
                                type="radio"
                                name="district"
                                :value="d.id"
                                v-model="model.district"
                                @change="resetForm"
                            />
                            <span class="label-text uppercase text-xs">{{
                                d.name
                            }}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <!-- End Disctrict -->

            <!-- Municipality -->
            <div
                class="flex border rounded m-4 px-1"
                v-if="
                    model.report_level != 'district' &&
                    model.report_level != 'province'
                "
            >
                <div
                    class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    Municipality
                </div>
                <div
                    v-if="
                        clusteredPrecinct.has_limited_access &&
                        clusteredPrecinct.municipality
                    "
                    class="w-auto mx-auto btn bg-transparent btn-sm btn-block text-gray-800 m-1 hover:bg-transparent border-0 text-[10px]"
                >
                    {{
                        Array.isArray(clusteredPrecinct.municipality)
                            ? clusteredPrecinct.municipality[0]
                            : clusteredPrecinct.municipality
                    }}
                </div>
                <div v-else class="w-full dropdown dropdown-bottom">
                    <label
                        tabindex="0"
                        class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-transparent border-0 text-[10px]"
                        @mousedown="closeDropdownOnClick"
                    >
                        Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content p-1 shadow bg-base-100 rounded-box w-full absolute z-[1000] min-w-max list-none overflow-y-auto h-[350px]"
                    >
                        <li v-if="model.report_level == 'municipality'">
                            <label
                                class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                            >
                                <input
                                    type="checkbox"
                                    class="checkbox checkbox-sm"
                                    id="all"
                                    v-model="allCitySelected"
                                    @change="toggleAllCity"
                                />
                                <span class="label-text text-xs">ALL</span>
                            </label>
                        </li>
                        <li v-for="city in municipalities" :key="city">
                            <label
                                class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                            >
                                <input
                                    v-if="model.report_level == 'municipality'"
                                    type="checkbox"
                                    class="checkbox checkbox-sm"
                                    :id="city"
                                    :value="city"
                                    @change="toggleMunicipality"
                                    v-model="model.municipalities"
                                />
                                <input
                                    v-if="model.report_level == 'barangay'"
                                    type="radio"
                                    class="radio radio-xs"
                                    :id="city"
                                    :value="city"
                                    @change="toggleMunicipality"
                                    @click="closeDropdownOnClick"
                                    v-model="model.municipalities"
                                />
                                <span class="label-text text-xs"
                                    >{{ city }}
                                </span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="px-4 flex items-center justify-center"
                v-if="!clusteredPrecinct.has_limited_access"
            >
                <div
                    class="grid gap-1 grid-cols-3"
                    v-if="model.report_level == 'municipality'"
                >
                    <div
                        class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center align-middle py-1 cursor-pointer"
                        v-for="city in model.municipalities"
                        @click="removeMunicipality(city)"
                    >
                        <p class="uppercase text-[10px]">{{ city }}</p>
                    </div>
                </div>
                <div
                    v-if="
                        model.report_level == 'barangay' &&
                        model.municipalities.length
                    "
                    class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center whitespace-nowrap align-middle py-1 text-[10px] cursor-pointer"
                    @click="removeMunicipality(city)"
                >
                    <span>{{ model.municipalities }}</span>
                </div>
            </div>
            <!-- End Municipality -->

            <!-- Barangay -->
            <div
                class="flex border rounded m-4 px-1"
                v-if="model.report_level == 'barangay'"
            >
                <div
                    class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    Barangay
                </div>

                <div class="w-full dropdown dropdown-bottom">
                    <label
                        tabindex="0"
                        class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-transparent border-0 text-[10px]"
                        @mousedown="closeDropdownOnClick"
                        >Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content p-1 shadow bg-base-100 rounded-box w-full absolute z-[1000] min-w-max list-none overflow-y-auto h-[350px]"
                    >
                        <li>
                            <label
                                class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                            >
                                <input
                                    type="checkbox"
                                    class="checkbox checkbox-sm"
                                    id="all"
                                    v-model="allBarangaySelected"
                                    @change="toggleAllBarangay"
                                />
                                <span class="label-text text-xs">ALL</span>
                            </label>
                        </li>
                        <li
                            v-for="(b, i) in clusteredPrecinct.barangay"
                            :key="i"
                        >
                            <label
                                class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                            >
                                <input
                                    type="checkbox"
                                    class="checkbox checkbox-sm"
                                    :id="i"
                                    :value="b.barangay_name"
                                    v-model="model.barangays"
                                />
                                <span class="label-text text-xs"
                                    >{{ b.barangay_name }}
                                </span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="px-4 flex items-center justify-center">
                <div class="grid gap-1 grid-cols-3">
                    <div
                        class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center whitespace-nowrap align-middle py-1 cursor-pointer"
                        v-for="barangay in model.barangays"
                        @click="removeBarangay(barangay)"
                    >
                        <p class="uppercase text-[10px]">{{ barangay }}</p>
                    </div>
                </div>
            </div>
            <!-- End Barangay -->

            <!-- Position -->
            <template v-if="model.report_level != 'province'">
                <div class="flex border rounded m-4 px-1">
                    <div
                        class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                    >
                        Position
                    </div>

                    <div class="w-full dropdown dropdown-bottom">
                        <label
                            tabindex="0"
                            class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-transparent border-0 text-[10px]"
                            @mousedown="closeDropdownOnClick"
                            >Click to select</label
                        >
                        <ul
                            tabindex="0"
                            class="dropdown-content p-1 shadow bg-base-100 rounded-box w-full absolute z-[400] min-w-max list-none overflow-y-auto h-[250px]"
                        >
                            <li>
                                <label
                                    class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                                >
                                    <input
                                        type="checkbox"
                                        class="checkbox checkbox-sm"
                                        id="all"
                                        v-model="allPositionSelected"
                                        @change="toggleAllPosition"
                                    />
                                    <span class="label-text text-xs">ALL</span>
                                </label>
                            </li>
                            <li
                                v-for="(position, index) in filteredPositions"
                                :key="index"
                            >
                                <label
                                    class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                                >
                                    <input
                                        type="checkbox"
                                        class="checkbox checkbox-sm"
                                        :id="position"
                                        :value="position"
                                        @change="resetPosition"
                                        v-model="model.positions"
                                    />
                                    <span class="label-text text-xs">{{
                                        position
                                    }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="px-4 flex items-center justify-center">
                    <div class="grid gap-1 grid-cols-3">
                        <div
                            class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 py-1 text-center align-middle cursor-pointer"
                            v-for="(position, index) in model.positions"
                            :key="position + index"
                            @click="removePosition(position)"
                        >
                            <p class="text-[10px]">{{ position }}</p>
                        </div>
                    </div>
                </div>
            </template>
            <!-- End Position -->

            <div class="flex py-6">
                <button
                    class="btn mx-auto rounded w-[130px] shadow-lg"
                    @click="resetButton"
                >
                    Reset
                </button>
                <button
                    class="btn mx-auto btn-success text-white w-[130px] rounded shadow-lg"
                    :class="
                        (model.report_level == 'municipality' &&
                            !model.municipalities.length) ||
                        (model.report_level == 'barangay' &&
                            !model.barangays.length) ||
                        !model.positions.length
                            ? 'btn-disabled'
                            : ''
                    "
                    @click="clusteredPrecinct.getResult(model)"
                >
                    Generate
                </button>
            </div>
        </form>
    </div>
</template>
