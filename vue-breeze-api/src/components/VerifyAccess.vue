<script setup>
import { ref } from "vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";

const clusteredPrecinct = clusteredPrecinctStore();
const form = ref({
    access_code: "",
});
</script>
<template>
    <div
        class="max-h-screen bg-gray-100 flex flex-col justify-center sm:py-12 min-h-screen"
    >
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-300 to-emerald-400 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"
            ></div>
            <div
                class="relative px-4 bg-white shadow-lg sm:rounded-3xl sm:p-20"
            >
                <div class="max-w-md mx-auto">
                    <!-- <div>
                        <h1 class="text-2xl font-semibold">
                            Verify Access Code
                        </h1>
                    </div> -->
                    <div class="divide-y divide-gray-200">
                        <form
                            @submit.prevent="
                                clusteredPrecinct.verifyAccess(form)
                            "
                            class="text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
                        >
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="access_code"
                                    name="access_code"
                                    type="text"
                                    v-model="form.access_code"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="access_code"
                                />
                                <label
                                    for="access_code"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Access Code</label
                                >

                                <!-- <div v-if="authStore.errors.username">
                                    <span class="text-red-400 text-xs"
                                        >Something went wrong, please try
                                        again</span
                                    >
                                </div> -->
                            </div>

                            <div class="relative">
                                <button
                                    class="hover:shadow hover:bg-blue-400 bg-blue-500 btn-block text-white rounded-md px-4 py-1 mt-4"
                                >
                                    Verify
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
