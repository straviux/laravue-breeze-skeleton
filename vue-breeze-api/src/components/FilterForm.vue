<script setup>
import { ref, computed } from "vue";
const model = ref({ district: "all" });
const allCitySelected = ref(false);
const allPositionSelected = ref(false);
const congressionalDistricts = [
    {
        id: 1,
        name: "1st",
        cities: [
            { id: 1, name: "Agutaya", district: "1" },
            { id: 2, name: "Araceli", district: "1" },
            { id: 3, name: "Busuanga", district: "1" },
            { id: 4, name: "Cagayancillo", district: "1" },
            { id: 5, name: "Coron", district: "1" },
            { id: 6, name: "Culion", district: "1" },
            { id: 7, name: "Cuyo", district: "1" },
            { id: 8, name: "Dumaran", district: "1" },
            { id: 9, name: "El Nido", district: "1" },
            { id: 10, name: "Kalayaan", district: "1" },
            { id: 11, name: "Linapacan", district: "1" },
            { id: 12, name: "Magsaysay", district: "1" },
            { id: 13, name: "Roxas", district: "1" },
            { id: 14, name: "San Vicente", district: "1" },
            { id: 15, name: "Taytay", district: "1" },
        ],
    },
    {
        id: 2,
        name: "2nd",
        cities: [
            { id: 16, name: "Balabac", district: "2" },
            { id: 17, name: "Bataraza", district: "2" },
            { id: 18, name: "Brooke's Point", district: "2" },
            { id: 19, name: "Narra", district: "2" },
            { id: 20, name: "Quezon", district: "2" },
            { id: 21, name: "Rizal ", district: "2" },
            { id: 22, name: "Sofronio Española", district: "2" },
        ],
    },
    {
        id: 3,
        name: "3rd",
        cities: [
            { id: 23, name: "Aborlan", district: "3" },
            { id: 24, name: "Puerto Princesa", district: "3" },
        ],
    },
    // ...and so on
];

const positions = [
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
    console.log(model.value.district);
    return district ? district.cities : [];
});

function toggleAllCity() {
    if (allCitySelected.value) {
        model.value.cities = cities.value.flatMap((city) => city.name);
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
    <div
        class="relative z-20 bg-white rounded-2xl p-2 mx-auto w-full md:w-2/5 lg:1/3"
    >
        <h1 class="mt-2 ml-4">Get started!</h1>

        <!-- District -->
        <div class="flex border rounded m-4 px-1">
            <div
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
            </div>
        </div>

        <!-- End Disctrict -->

        <!-- Municipality -->
        <div class="flex border rounded m-4 px-1">
            <div
                class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-sm font-semibold"
            >
                Municipality
            </div>

            <div class="w-full dropdown dropdown-bottom">
                <label
                    ref="dropDown"
                    tabindex="0"
                    class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0"
                    >Click to select</label
                >
                <ul
                    tabindex="0"
                    class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full max-h-[300px]"
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
                            <span class="label-text">All</span>
                        </label>
                    </li>
                    <li v-for="city in cities" :key="city">
                        <label class="cursor-pointer">
                            <input
                                type="checkbox"
                                class="checkbox checkbox-sm"
                                :id="city.id"
                                :value="city.name"
                                @change="allCitySelected = false"
                                v-model="model.cities"
                            />
                            <span class="label-text">{{ city.name }}</span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>

        <div class="px-4 flex items-center justify-center">
            <div class="grid gap-2 lg:grid-cols-3 md:grid-cols-3 grid-cols-2">
                <div
                    class="bg-gray-100 p-4 text-center"
                    v-for="city in model.cities"
                >
                    {{ city }}
                </div>
            </div>
        </div>
        <!-- End Municipality -->

        <!-- Position -->
        <div class="flex border rounded m-4 px-1">
            <div
                class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-sm font-semibold"
            >
                Position
            </div>

            <div class="w-full dropdown dropdown-bottom">
                <label
                    ref="dropDown"
                    tabindex="0"
                    class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0"
                    >Click to select</label
                >
                <ul
                    tabindex="0"
                    class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full max-h-[300px]"
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
                            <span class="label-text">All</span>
                        </label>
                    </li>
                    <li v-for="position in positions" :key="position">
                        <label class="cursor-pointer">
                            <input
                                type="checkbox"
                                class="checkbox checkbox-sm"
                                :id="position"
                                :value="position"
                                @change="allPositionSelected = false"
                                v-model="model.positions"
                            />
                            <span class="label-text">{{ position }}</span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="px-4 flex items-center justify-center">
            <div class="grid gap-2 lg:grid-cols-3 md:grid-cols-3 grid-cols-2">
                <div
                    class="bg-gray-100 p-4 text-center"
                    v-for="(position, index) in model.positions"
                    :key="position + index"
                >
                    {{ position }}
                </div>
            </div>
        </div>
        <!-- End Position -->

        <div class="flex py-6">
            <button class="btn mx-auto rounded" @click="handleSubmit">
                Next
            </button>
        </div>
    </div>
</template>
