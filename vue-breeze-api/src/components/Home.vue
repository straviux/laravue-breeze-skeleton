<script setup>
import { onMounted } from "vue";

import { useAuthStore } from "../store/auth";
import FilterForm from "./FilterForm.vue";

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.getUser();
});
</script>
<template>
    <div class="relative">
        <div
            class="mx-auto py-12 bg-white rounded-lg w-full md:w-2/5 lg:1/3"
            v-if="authStore.user"
        >
            <pre>
            {{ authStore.user.name }}
            {{ authStore.user.email }}
            </pre>
            <filter-form class="p-2"></filter-form>
        </div>
        <div class="mx-auto py-12" v-else>
            You are not authorized to view this page. Please
            <router-link to="/login" class="text-underline text-blue-500"
                >login</router-link
            >
            to continue
        </div>
    </div>
</template>
