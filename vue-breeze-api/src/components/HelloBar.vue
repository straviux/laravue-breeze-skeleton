<script setup>
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";
const clusteredPrecinct = clusteredPrecinctStore();
</script>
<template>
    <div class="flex flex-col md:flex-row gap-10 justify-between items-center">
        <div>
            <h1
                class="font-bold md:font-extrabold text-xl md:text-3xl text-gray-700 drop-shadow"
            >
                {{ clusteredPrecinct.election_year }} National and Local
                Elections Results
            </h1>
            <h2
                class="font-semibold text-center md:text-left text-xl md:text-3xl uppercase text-gray-600 whitespace-normal md:whitespace-nowrap"
            >
                Province of {{ clusteredPrecinct.province }}
            </h2>
            <h3
                v-if="!Array.isArray(clusteredPrecinct.municipality)"
                class="py-0 font-semibold text-gray-600 text-xl md:text-xl lg:text-2xl whitespace-normal md:whitespace-nowrap"
            >
                <div
                    v-if="
                        clusteredPrecinct.municipality &&
                        !/CITY/i.test(clusteredPrecinct.municipality)
                    "
                >
                    Municipality of {{ clusteredPrecinct.municipality }}
                </div>
                <div v-else>
                    {{ clusteredPrecinct.municipality }}
                </div>
            </h3>
            <p class="mx-auto w-48 px-1 md:mx-0 mt-1">
                <iframe
                    src="https://free.timeanddate.com/clock/i8sjc6kt/n4159/tlph/fc555/tct/pct/ahl/tt0/tw0/td2/ta1/tb2"
                    frameborder="0"
                    width="225"
                    height="19"
                    allowtransparency="true"
                ></iframe>
            </p>
        </div>
        <div class="flex justify-between gap-2 items-center">
            <!-- <button
        class="bg-sky-800 py-2 px-4 text-gray-100 rounded-lg hover:shadow-2xl cursor-pointer"
      >
        Add Post
      </button> -->
            <div class="flex justify-center items-center">
                <button
                    @click="clusteredPrecinct.logout"
                    class="text-sm px-2 text-red-500 underline rounded-full flex gap-1 drop-shadow-lg md:-mt-24 -mt-6"
                >
                    <mdicon name="logout-variant" width="15px" />
                    <span class="font-bold drop-shadow-lg">Logout</span>
                </button>
            </div>
        </div>
    </div>
</template>
