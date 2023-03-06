<script setup>
import NavBar from "./NavBar.vue";
import Content from "./Content.vue";
// import Notification from "../components/Notification.vue";
// import SideBarRight from "./SideBarRight.vue";
// import Notification from "./Notification.vue";
//
</script>

<template>
    <div class="grid grid-cols-12 h-screen">
        <div
            class="col-span-12 bg-stone-50 flex flex-col lg:flex-row md:divide-x-2"
        >
            <div
                class="lg:max-w-[350px] w-full lg:h-full h-screen relative lg:fixed lg:z-40 no-print"
            >
                <nav-bar />
            </div>

            <Content class="flex-1 lg:ml-[350px] print-div" />
            <!-- <side-bar-right class="p-10" /> -->
            <!-- <div class="lg:w-[350px] no-print"></div> -->
        </div>
    </div>
    <!-- <Notification /> -->
</template>
