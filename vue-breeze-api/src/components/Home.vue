<script setup>
import { onMounted } from "vue";

// import { useAuthStore } from "../store/auth";

import FilterForm from "./FilterForm.vue";
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
// const authStore = useAuthStore();

onMounted(async () => {
    await clusteredPrecinct.verifyAccess({
        access_code: clusteredPrecinct.access_code,
    });
    // console.log(clusteredPrecinct.verify_access);
});
</script>
<template>
    <div class="relative">
        <div class="bg-white rounded w-full">
            <!-- <pre>
            {{ authStore.user.name }}
            {{ authStore.user.email }}
            </pre> -->
            <!-- <filter-form class="p-2"></filter-form> -->
            <!-- <div v-if="authStore.user"> -->
            <div>
                <!-- <p class="text-center no-print pt-10">
                    To view results, please select the geographical level and
                    voting jurisdiction information
                </p> -->
                <router-view></router-view>
            </div>
            <!-- <div class="mx-auto py-12" v-else>
                You are not authorized to view this page. Please
                <router-link to="/login" class="text-underline text-blue-500"
                    >login</router-link
                >
                to continue
            </div> -->
        </div>
    </div>
</template>
