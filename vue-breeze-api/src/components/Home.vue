<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import FilterForm from "./FilterForm.vue";

const user = ref();
const getToken = async () => {
    await axios.get("/sanctum/csrf-cookie");
};
onMounted(async () => {
    await getToken();
    const data = await axios.get("/api/user");
    console.log(data);
});
</script>
<template>
    <div class="mx-auto py-12">
        <filter-form></filter-form>
    </div>
</template>
