<script setup>
import { ref } from "vue";
import { useAuthStore } from "../store/auth";

const authStore = useAuthStore();
const form = ref({
    input_type: "",
    password: "",
});
</script>
<template>
    <div
        class="max-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12 min-h-screen"
    >
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-300 to-emerald-400 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"
            ></div>
            <div
                class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20"
            >
                <div class="max-w-md mx-auto">
                    <div>
                        <h1 class="text-2xl font-semibold">Login</h1>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <form
                            @submit.prevent="authStore.handleLogin(form)"
                            class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
                        >
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="username"
                                    name="input_type"
                                    type="text"
                                    v-model="form.input_type"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="username"
                                />
                                <label
                                    for="username"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Username/Email</label
                                >

                                <div v-if="authStore.errors.username">
                                    <span
                                        class="text-red-400 text-sm m-2 p-2"
                                        >{{
                                            authStore.errors.username[0]
                                        }}</span
                                    >
                                </div>
                            </div>
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="password"
                                    name="password"
                                    type="password"
                                    v-model="form.password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="Password"
                                />
                                <label
                                    for="password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Password</label
                                >
                                <div v-if="authStore.errors.password">
                                    <span class="text-red-400 text-sm m-2 p-2"
                                        >Please enter your password</span
                                    >
                                </div>
                            </div>
                            <div class="relative">
                                <button
                                    class="hover:shadow hover:bg-blue-400 bg-blue-500 text-white rounded-md px-4 py-1 mt-4"
                                >
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
