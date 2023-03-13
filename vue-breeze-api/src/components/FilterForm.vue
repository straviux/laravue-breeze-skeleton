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
        valuesToRemove = ["MAYOR", "VICE-MAYOR", "COUNCILOR"];
        return positions.filter((pos) => !valuesToRemove.includes(pos));
    } else {
        return positions;
    }
});

const municipalities = computed(() => {
    const district = congressionalDistricts.find(
        (d) => d.id == model.value.district
    );
    return district ? district.municipalities : [];
});

function resetForm() {
    model.value.municipalities = [];
    model.value.barangays = [];
    // model.value.district = null;
    model.value.positions = filteredPositions.value;
    allCitySelected.value = false;
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
    if (model.value.report_level == "barangay") {
        clusteredPrecinct.getBarangay(model.value.municipalities);
    }
}

// function resetDistrict() {
//     allCitySelected.value = false;
//     model.value.municipalities = [];
// }

function resetPosition() {
    allPositionSelected.value = false;
    // model.value.municipalities = [];
}

onMounted(() => {
    model.value.positions = filteredPositions.value.flatMap((p) => p);

    const dropdownContent = document.querySelectorAll(".oneclick-dropdown>li");
    dropdownContent.forEach((element) => {
        element.addEventListener("click", () => {
            document.activeElement.blur();
        });
    });
});
</script>
<template>
    <div class="relative">
        <form @submit.prevent="">
            <p class="m-4 px-1 mb-4 text-sm font-semibold">
                Select geographical level and voting jurisdiction:
            </p>

            <!-- Report Options -->
            <div class="grid grid-cols-2 border rounded m-4 px-1">
                <div class="py-2">
                    <label class="flex p-2 cursor-pointer gap-1">
                        <input
                            class="my-auto radio radio-xs"
                            type="radio"
                            name="report_level"
                            value="province"
                            v-model="model.report_level"
                            @change="resetForm"
                        />
                        <span class="label-text uppercase text-xs"
                            >Provice (Overall)</span
                        >
                    </label>
                </div>
                <div class="py-2">
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
                v-if="model.report_level !== 'province'"
            >
                <div
                    class="py-3 my-auto px-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    District
                </div>

                <!-- <label
                        ref="dropDown"
                        tabindex="0"
                        class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-[10px]"
                        >Click to select</label
                    > -->
                <ul tabindex="0" class="flex p-2 gap-1 w-full max-h-[300px]">
                    <!-- <li>
                            <label class="flex p-2 cursor-pointer ml-3">
                                <input
                                    class="my-auto radio radio-xs"
                                    type="radio"
                                    name="district"
                                    value="all"
                                    v-model="model.district"
                                    @change="resetForm"
                                    checked
                                />
                                <span class="label-text uppercase text-xs"
                                    >ALL</span
                                >
                            </label>
                        </li> -->
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

                <div class="w-full dropdown dropdown-bottom">
                    <label
                        ref="dropDown"
                        tabindex="0"
                        class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-[10px]"
                        >Click to select</label
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
            <div class="px-4 flex items-center justify-center">
                <div
                    class="grid gap-1 grid-cols-3"
                    v-if="model.report_level == 'municipality'"
                >
                    <div
                        class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center align-middle py-1"
                        v-for="city in model.municipalities"
                    >
                        <p class="uppercase text-[10px]">{{ city }}</p>
                    </div>
                </div>
                <div
                    v-if="
                        model.report_level == 'barangay' &&
                        model.municipalities.length
                    "
                    class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center whitespace-nowrap align-middle py-1 text-[10px]"
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
                        ref="dropDown"
                        tabindex="0"
                        class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-[10px]"
                        >Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content p-1 shadow bg-base-100 rounded-box w-full absolute z-[1000] min-w-max list-none overflow-y-auto h-[350px]"
                    >
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
                        class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 text-center whitespace-nowrap align-middle py-1"
                        v-for="barangay in model.barangays"
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
                            ref="dropDown"
                            tabindex="0"
                            class="btn bg-transparent btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-[10px]"
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
                            class="bg-gray-200 shadow text-gray-600 rounded font-semibold px-2 py-1 text-center align-middle"
                            v-for="(position, index) in model.positions"
                            :key="position + index"
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
                    @click="resetForm"
                >
                    Reset
                </button>
                <button
                    class="btn mx-auto btn-success text-white w-[130px] rounded shadow-lg"
                    @click="clusteredPrecinct.getResult(model)"
                >
                    Generate
                </button>
            </div>
        </form>
    </div>
</template>
