<script setup>
import { onMounted } from "vue";

import { useAuthStore } from "../store/auth";

import FilterForm from "./FilterForm.vue";

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.getUser();
    localStorage.clear();
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
            <div v-if="authStore.user">
                <!-- <p class="text-center no-print pt-10">
                    To view results, please select the geographical level and
                    voting jurisdiction information
                </p> -->
                <router-view></router-view>
            </div>
            <div class="mx-auto py-12" v-else>
                You are not authorized to view this page. Please
                <router-link to="/login" class="text-underline text-blue-500"
                    >login</router-link
                >
                to continue
            </div>
        </div>
    </div>
</template>
