<template>
    <div
        v-if="isFullScreen"
        wire:loading
        class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-[1000] overflow-hidden opacity-95 flex flex-col items-center justify-center"
        :class="bgColor ? bgColor : 'bg-emerald-600'"
    >
        <div
            class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"
        ></div>
        <h2 class="text-center text-white text-xl font-semibold"></h2>
        <p class="w-1/3 text-center text-white">
            This may take a few seconds, please don't close this page.
            <slot v></slot>
        </p>
    </div>
    <div v-else class="flex items-center justify-center text-center">
        <div
            class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-8 w-8 mr-4"
        ></div>
        <p>Loading...</p>
    </div>
</template>

<script setup>
const props = defineProps({
    isFullScreen: Boolean,
    bgColor: String,
});
</script>

<style scoped>
.loader {
    border-top-color: #3498db;
    -webkit-animation: spinner 1.5s linear infinite;
    animation: spinner 1.5s linear infinite;
}
@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    }
}
@keyframes spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
