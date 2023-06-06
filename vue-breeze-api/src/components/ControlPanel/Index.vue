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
const accessHistory = ref([]);
const updateBtnLabel = ref("Update");
const isLoadingBtn = ref(false);

function selectRow(row) {
    // console.log(row);
    model.value.access_code = row.access_code;
    model.value.province = row.province;
    model.value.municipality = row.municipality;
    model.value.has_access = row.is_accessible;
    clusteredPrecinct.showAccessHistory(row.id); //row.id = access_code_id
}

function update() {
    // console.log(model.value);
    clusteredPrecinct.updateAccessCode(model.value);

    if (clusteredPrecinct.loading) {
        updateBtnLabel.value = "Updating";
        isLoadingBtn.value = true;
    }
    // if (!clusteredPrecinct.loading) {
    setTimeout(() => {
        if (!clusteredPrecinct.loading) {
            updateBtnLabel.value = "Updated";
        }
    }, 1000);

    setTimeout(() => {
        isLoadingBtn.value = false;
        updateBtnLabel.value = "Update";
    }, 2500);
    // }
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
        <div class="mx-auto mt-12">
            <table class="table text-[0.8rem]">
                <!-- head -->

                <thead>
                    <tr>
                        <th colspan="9">
                            <button
                                @click="clusteredPrecinct.logout"
                                class="text-sm text-red-500 underline rounded-full flex gap-1 drop-shadow-lg mx-auto"
                            >
                                <mdicon name="logout-variant" width="15px" />
                                <span class="font-bold drop-shadow-lg"
                                    >Log out</span
                                >
                            </button>
                        </th>
                    </tr>
                    <tr class="rounded-t">
                        <th>#</th>
                        <!-- <th>Access Code</th> -->
                        <th>Province</th>
                        <th>City/Municipality</th>
                        <th>Has Access</th>
                        <th>Visit Count</th>
                        <!-- <th>Last Update</th> -->
                        <th>Last Visited</th>
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
                        <!-- <td class="font-semibold text-gray-600">
                            {{ ac.access_code }}
                        </td> -->
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
                        <!-- <td>
                            {{
                                moment(ac.updated_at).isValid()
                                    ? moment(ac.updated_at).format("L LT")
                                    : ""
                            }}
                        </td> -->
                        <td>
                            {{
                                moment(ac.last_visited).isValid()
                                    ? moment(ac.last_visited).format("L LT")
                                    : ""
                            }}
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <label
                                    class="cursor-pointer"
                                    for="update-modal"
                                    @click="selectRow(ac)"
                                >
                                    <mdicon
                                        name="square-edit-outline"
                                        size="24"
                                        class="text-orange-500"
                                    />
                                </label>
                                <label
                                    class="cursor-pointer"
                                    for="history-modal"
                                    @click="selectRow(ac)"
                                >
                                    <mdicon
                                        name="history"
                                        size="24"
                                        class="text-yellow-500"
                                    />
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="checkbox" id="update-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative w-[350px]">
            <label
                for="update-modal"
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
                            disabled
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
                            disabled
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
                <button
                    class="btn transition-all"
                    :class="{
                        'loading btn-disable btn-success text-white':
                            isLoadingBtn,
                    }"
                    @click="update"
                >
                    {{ updateBtnLabel }}
                </button>
            </div>
        </div>
    </div>

    <input type="checkbox" id="history-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative w-[350px]">
            <label
                for="history-modal"
                class="btn btn-sm btn-circle absolute right-3 top-2"
                >✕</label
            >
            <!-- <h3 class="font-bold text-lg">UPDATE FORM</h3> -->
            <form class="w-full max-w-lg mt-4">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-sm font-semibold mb-2"
                            for="province"
                        >
                            {{ model.province }}
                            <span v-if="model.municipality">
                                / {{ model.municipality }}</span
                            >
                        </label>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <!-- {{ clusteredPrecinct.accessHistory }} -->
                        <div
                            v-if="
                                !clusteredPrecinct.loading &&
                                clusteredPrecinct.accessHistory
                            "
                        >
                            <div class="flex gap-2">
                                <div class="flex gap-2">
                                    <p class="font-light">Accessed:</p>
                                    <span class="text-gray-500">{{
                                        clusteredPrecinct.accessHistory.length
                                    }}</span>
                                </div>
                                <div>-</div>
                                <div class="flex gap-2">
                                    <p class="font-light">Generated:</p>
                                    <span class="text-gray-500">{{
                                        clusteredPrecinct.accessHistory.length
                                    }}</span>
                                </div>
                            </div>
                            <table class="table text-[0.7rem] table-compact">
                                <thead>
                                    <th class="text-gray-500 text-[12px]">#</th>
                                    <th
                                        class="text-gray-500 w-full text-[12px] font-semibold"
                                    >
                                        Generated At
                                    </th>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            history, i
                                        ) in clusteredPrecinct.accessHistory"
                                        :key="i"
                                    >
                                        <td
                                            class="text-gray-500 font-mono font-light"
                                        >
                                            {{ i + 1 }}.
                                        </td>
                                        <td class="py-2 font-mono">
                                            {{
                                                moment(
                                                    history.access_at
                                                ).format("L LT")
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else-if="!clusteredPrecinct.loading">
                            No data available
                        </div>
                        <div v-else>Loading data. Please wait..</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
