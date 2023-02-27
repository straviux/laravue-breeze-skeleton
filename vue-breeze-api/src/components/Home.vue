<script setup>
import { onMounted } from "vue";

import { useAuthStore } from "../store/auth";

// import FilterForm from "./FilterForm.vue";

const authStore = useAuthStore();

onMounted(async () => {
    await authStore.getUser();
    localStorage.clear();
});
</script>
<template>
    <div class="relative">
        <div
            class="py-12 bg-white border-b border-r border-t w-full md:w-1/4"
            v-if="authStore.user"
        >
            <!-- <pre>
            {{ authStore.user.name }}
            {{ authStore.user.email }}
            </pre> -->
            <!-- <filter-form class="p-2"></filter-form> -->
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
