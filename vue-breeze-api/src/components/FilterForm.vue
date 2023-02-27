<script setup>
import { ref, computed } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
const model = ref({ district: "all", cities: [], positions: [] });
const allCitySelected = ref(false);
const allPositionSelected = ref(false);
const congressionalDistricts = [
    {
        id: 1,
        name: "1st",
        cities: [
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
        cities: [
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
        cities: ["ABORLAN", "PUERTO PRINCESA CITY"],
    },
    // ...and so on
];

const positions = [
    "PRESIDENT",
    "V-PRESIDENT",
    "SENATOR",
    "PARTY LIST",
    "GOVERNOR",
    "VICE-GOVERNOR",
    "CONGRESSMAN",
    "BOARD MEMBER",
    "MAYOR",
    "VICE-MAYOR",
    "COUNCILOR",
];

const cities = computed(() => {
    const district = congressionalDistricts.find(
        (d) => d.id == model.value.district
    );
    return district ? district.cities : [];
});

function toggleAllCity() {
    if (allCitySelected.value) {
        model.value.cities = cities.value.flatMap((city) => city);
    } else {
        model.value.cities = [];
    }
}

function toggleAllPosition() {
    if (allPositionSelected.value) {
        model.value.positions = positions.flatMap((p) => p);
    } else {
        model.value.positions = [];
    }
}
function resetDistrict() {
    allCitySelected.value = false;
    model.value.cities = [];
}

const handleSubmit = () => {
    console.log(model.value);
};
</script>
<template>
    <div class="relative">
        <form @submit.prevent="clusteredPrecinct.getResult(model)">
            <p class="m-4 px-1 mb-10 text-sm font-semibold">
                Select geographical level and voting jurisdiction:
            </p>
            <!-- District -->
            <div class="flex border rounded m-4 px-1">
                <div
                    class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    District
                </div>

                <div class="w-full dropdown dropdown-bottom">
                    <label
                        ref="dropDown"
                        tabindex="0"
                        class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-xs"
                        >Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full max-h-[300px]"
                    >
                        <li>
                            <label class="flex p-2 cursor-pointer ml-3">
                                <input
                                    class="my-auto radio radio-xs"
                                    type="radio"
                                    name="district"
                                    value="all"
                                    v-model="model.district"
                                    checked
                                />
                                <span class="label-text uppercase text-xs"
                                    >ALL</span
                                >
                            </label>
                        </li>
                        <li v-for="d in congressionalDistricts">
                            <label class="flex p-2 cursor-pointer ml-3">
                                <input
                                    class="my-auto radio radio-xs"
                                    type="radio"
                                    name="district"
                                    :value="d.id"
                                    v-model="model.district"
                                    @change="resetDistrict"
                                />
                                <span class="label-text uppercase text-xs">{{
                                    d.name
                                }}</span>
                            </label>
                        </li>
                    </ul>
                </div>
                <!-- <div
                class="py-3 my-auto px-2 border-r-2 w-[100px] border-gray-300 text-gray-600 text-sm font-semibold"
            >
                District
            </div>
            <div class="flex items-center justify-center">
                <label
                    class="flex p-2 cursor-pointer ml-3"
                    v-for="d in congressionalDistricts"
                >
                    <input
                        class="my-auto radio"
                        type="radio"
                        name="district"
                        :value="d.id"
                        v-model="model.district"
                        @change="resetDistrict"
                    />
                    <div class="title px-2">{{ d.name }}</div>
                </label>

                <label class="flex p-2 cursor-pointer">
                    <input
                        class="my-auto radio"
                        type="radio"
                        name="district"
                        value="all"
                        v-model="model.district"
                        checked
                    />
                    <div class="title px-2">All</div>
                </label>
            </div> -->
            </div>
            <div class="px-4 flex items-center justify-center">
                <div class="text-xs">
                    <p class="uppercase">
                        Selected District:
                        <span class="font-semibold">{{ model.district }}</span>
                    </p>
                </div>
            </div>
            <!-- End Disctrict -->

            <!-- Municipality -->
            <div class="flex border rounded m-4 px-1">
                <div
                    class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
                >
                    Municipality
                </div>

                <div class="w-full dropdown dropdown-bottom">
                    <label
                        ref="dropDown"
                        tabindex="0"
                        class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-xs"
                        >Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content menu p-1 shadow bg-base-100 rounded-box w-full"
                    >
                        <li>
                            <label class="cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked="checked"
                                    class="checkbox checkbox-sm"
                                    id="all"
                                    v-model="allCitySelected"
                                    @change="toggleAllCity"
                                />
                                <span class="label-text text-xs">All</span>
                            </label>
                        </li>
                        <li v-for="city in cities" :key="city">
                            <label class="cursor-pointer">
                                <input
                                    type="checkbox"
                                    class="checkbox checkbox-sm"
                                    :id="city"
                                    :value="city"
                                    @change="allCitySelected = false"
                                    v-model="model.cities"
                                />
                                <span class="label-text text-xs">{{
                                    city
                                }}</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="px-4 flex items-center justify-center">
                <div
                    class="grid gap-2 lg:grid-cols-3 md:grid-cols-3 grid-cols-2"
                >
                    <div
                        class="bg-gray-100 shadow py-2 text-gray-500 px-1 text-center text-xs"
                        v-for="city in model.cities"
                    >
                        <span class="uppercase">{{ city }}</span>
                    </div>
                </div>
            </div>
            <!-- End Municipality -->

            <!-- Position -->
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
                        class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-xs"
                        >Click to select</label
                    >
                    <ul
                        tabindex="0"
                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full"
                    >
                        <li>
                            <label class="cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked="checked"
                                    class="checkbox checkbox-sm"
                                    id="all"
                                    v-model="allPositionSelected"
                                    @change="toggleAllPosition"
                                />
                                <span class="label-text text-xs">All</span>
                            </label>
                        </li>
                        <li v-for="(position, index) in positions" :key="index">
                            <label class="cursor-pointer">
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
                <div
                    class="grid gap-2 lg:grid-cols-3 md:grid-cols-3 grid-cols-2"
                >
                    <div
                        class="bg-gray-100 shadow py-2 text-gray-500 px-1 text-center text-xs"
                        v-for="(position, index) in model.positions"
                        :key="position + index"
                    >
                        {{ position }}
                    </div>
                </div>
            </div>
            <!-- End Position -->

            <div class="flex py-6">
                <button class="btn mx-auto rounded">Generate</button>
            </div>
        </form>
    </div>
</template>
