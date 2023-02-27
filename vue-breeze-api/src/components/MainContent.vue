<template>
    <div class="flex flex-col gap-6 p-7 relative bg-gray-400">
        <!-- <hello-bar class="z-20" /> -->
        <router-view class="z-20"></router-view>
    </div>
</template>

<style scoped>
.bg-image {
    background: url("../assets/img/grid.jpg") no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>
