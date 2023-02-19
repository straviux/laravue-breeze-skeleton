<script setup>
import DefaultLayout from "./components/DefaultLayout.vue";
</script>

<template>
    <main class="relative flex min-h-screen flex-col bg-gray-100">
        <div
            class="absolute inset-0 bg-[url(assets/img/grid.svg)] bg-center [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.1))]"
        ></div>

        <RouterView />
    </main>
</template>
