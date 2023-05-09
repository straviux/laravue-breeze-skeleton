<script setup>
import moment from "moment";
import { ref, computed, onMounted } from "vue";
import { clusteredPrecinctStore } from "../../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
const model = ref({
    access_code: null,
    province: null,
    municipality: null,
    has_access: null,
});

function selectRow(row) {
    // console.log(row.is_accessible);
    model.value.access_code = row.access_code;
    model.value.province = row.province;
    model.value.municipality = row.municipality;
    model.value.has_access = row.is_accessible;
}

function update() {
    console.log(model.value);
    clusteredPrecinct.updateAccessCode(model.value);
}

function getCodes() {
    clusteredPrecinct.getAccessCodes();
}
onMounted(() => {
    getCodes();
});
</script>
<template>
    <div class="flex h-screen bg-slate-700">
        <div
            class="card w-[95%] md:w-[50%] h-20 mx-auto mt-12 bg-gray-200 rounded-md shadow-lg"
        >
            <div class="flex justify-center items-center py-4">
                <button
                    @click="clusteredPrecinct.logout"
                    class="text-sm px-2 text-red-500 underline rounded-full flex gap-1 drop-shadow-lg"
                >
                    <mdicon name="logout-variant" width="15px" />
                    <span class="font-bold drop-shadow-lg">Log out</span>
                </button>
            </div>
            <table class="table w-full">
                <!-- head -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Access Code</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Has Access?</th>
                        <th>Usage</th>
                        <th>Last Generated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr
                        v-for="(ac, i) in clusteredPrecinct.access_codes"
                        :key="i"
                    >
                        <td class="text-gray-500">{{ i + 1 }}</td>
                        <td class="font-semibold text-gray-600">
                            {{ ac.access_code }}
                        </td>
                        <td>{{ ac.province }}</td>
                        <td>{{ ac.municipality }}</td>
                        <td
                            :class="
                                ac.is_accessible
                                    ? 'text-green-500'
                                    : 'text-red-500'
                            "
                        >
                            {{ ac.is_accessible ? "Yes" : "No" }}
                        </td>
                        <td>{{ ac.visit_count }}</td>
                        <td>
                            {{
                                ac.last_generated
                                    ? moment(ac.last_generated).format("L LT")
                                    : ""
                            }}
                        </td>
                        <td>
                            <label
                                class="cursor-pointer"
                                for="my-modal"
                                @click="selectRow(ac)"
                            >
                                <mdicon
                                    name="square-edit-outline"
                                    size="24"
                                    class="text-orange-500"
                                />
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="checkbox" id="my-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative w-[350px]">
            <label
                for="my-modal"
                class="btn btn-sm btn-circle absolute right-3 top-2"
                >✕</label
            >
            <!-- <h3 class="font-bold text-lg">UPDATE FORM</h3> -->
            <form class="w-full max-w-lg mt-4">
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            for="province"
                        >
                            Province
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="province"
                            type="text"
                            v-model="model.province"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            for="city"
                        >
                            City/Municipality
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="province"
                            type="text"
                            v-model="model.municipality"
                        />
                    </div>
                </div>
                <div class="form-control w-28">
                    <label class="label cursor-pointer">
                        <span
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            >Has Access</span
                        >
                        <input
                            type="checkbox"
                            :checked="model.has_access"
                            class="checkbox -mt-2"
                            v-model="model.has_access"
                        />
                    </label>
                </div>
            </form>
            <div class="modal-action">
                <label for="my-modal" class="btn" @click="update">Update</label>
            </div>
        </div>
    </div>
</template>
