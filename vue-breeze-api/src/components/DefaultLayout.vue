<template>
    <div>
        <Nav />

        <!-- <div class="grid grid-cols-12 min-h-screen">
            <div
                class="col-span-12 bg-stone-50 flex flex-col lg:flex-row md:divide-x-2"
            >
                <div class="lg:w-[250px] w-full h-full">
                    <FilterForm />
                </div>

                <main-content class="flex-1" />
            </div>
        </div> -->
<<<<<<< HEAD
        <router-view></router-view>
=======
        <RouterView />
>>>>>>> fd47b97fe18aa3d59d17f73e9b6771689bfa1ca8
    </div>
</template>
<script setup>
import Nav from "./Nav.vue";
import FilterForm from "./FilterForm.vue";
import MainContent from "./MainContent.vue";
</script>
