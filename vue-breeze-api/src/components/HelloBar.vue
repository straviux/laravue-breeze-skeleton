<template>
    <div class="flex flex-col md:flex-row gap-10 justify-between items-center">
        <div>
            <h1
                class="font-extrabold text-4xl text-blueGray-700 drop-shadow-lg"
            >
                2022 National and Local Elections Results
            </h1>
            <p>
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
            <!-- <div class="flex justify-center items-center" v-if="authStore.user">
                <button
                    @click="authStore.handleLogout"
                    class="text-sm px-2 text-red-500 underline rounded-full flex gap-1 drop-shadow-lg"
                >
                    <mdicon name="logout-variant" width="15px" />
                    <span class="font-bold drop-shadow-lg">Log out</span>
                </button>
            </div> -->
        </div>
    </div>
</template>
<script setup>
// import store from "../store";
// import { useRouter } from "vue-router";
import { useAuthStore } from "../store/auth";
const authStore = useAuthStore();

const getDateTime = () => {
    const current = new Date();
    const date =
        current.getFullYear() +
        "-" +
        (current.getMonth() + 1) +
        "-" +
        current.getDate();
    const time =
        current.getHours() +
        ":" +
        current.getMinutes() +
        ":" +
        current.getSeconds();
    const dateTime = date + " " + time;

    return dateTime;
};
</script>
