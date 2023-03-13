<script setup>
import HelloBar from "./HelloBar.vue";
</script>
<template>
    <div class="flex flex-col gap-6 p-2 md:p-7 relative">
        <div
            class="absolute bg-gradient-to-b from-gray-200 to-stone-100 opacity-75 inset-0 z-0 no-print"
        ></div>
        <div
            class="absolute inset-0 bg-[url(assets/img/grid.svg)] bg-center z-0 no-print"
        ></div>
        <hello-bar class="z-20 no-print" />
        <router-view class="z-20"></router-view>
    </div>
</template>
