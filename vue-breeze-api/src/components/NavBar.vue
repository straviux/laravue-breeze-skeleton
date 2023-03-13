<template>
    <div
        class="relative lg:block lg:space-y-10 p-2 md:grid md:grid-cols-12 lg:h-full"
    >
        <div v-if="!menuIsActive">
            <button
                v-if="!menuIsActive && !toggleFilterForm"
                class="btn btn-success gap-2 text-white"
                @click="toggleFilterForm = !toggleFilterForm"
            >
                <!-- <span v-if="!toggleFilterForm"> -->
                <mdicon name="play-circle-outline" />
                Get Started
                <!-- </span> -->
            </button>
            <button
                v-else
                class="btn btn-ghost absolute right-4 z-50 text-red-600"
                @click="toggleFilterForm = !toggleFilterForm"
            >
                <!-- <span v-if="!toggleFilterForm"> -->
                <mdicon name="close" size="22" />
                <!-- </span> -->
            </button>
        </div>
        <FilterForm v-if="menuIsActive || toggleFilterForm" />
    </div>
</template>
<script setup>
import { ref } from "vue";
import FilterForm from "./FilterForm.vue";

const toggleFilterForm = ref(false);

const MOBILE_BREAKPOINT = 1024;
const width = ref(document.body.offsetWidth);
const menuIsActive = ref(width.value > MOBILE_BREAKPOINT);
// const toggleMenu = () => (menuIsActive.value = !menuIsActive.value);
window.addEventListener("resize", function () {
    width.value = document.body.offsetWidth;
    // console.log(width.value);
    menuIsActive.value = width.value > MOBILE_BREAKPOINT;
});
</script>
