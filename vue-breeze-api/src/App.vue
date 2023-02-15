<script setup>
import DefaultLayout from "./components/DefaultLayout.vue";
</script>

<template>
    <main
        class="relative flex min-h-screen flex-col overflow-hidden bg-gray-100 py-6 sm:py-12"
    >
        <div
            class="absolute inset-0 bg-[url(assets/img/grid.svg)] bg-center [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.1))]"
        ></div>

        <RouterView />
    </main>
</template>
