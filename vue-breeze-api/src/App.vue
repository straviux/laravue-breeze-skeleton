<script setup>
let localStorageSize = function () {
    let _lsTotal = 0,
        _xLen,
        _x;
    for (_x in localStorage) {
        if (!localStorage.hasOwnProperty(_x)) continue;
        _xLen = (localStorage[_x].length + _x.length) * 2;
        _lsTotal += _xLen;
    }
    return (_lsTotal / 1024).toFixed(2);
};
console.log(`localstorage size: ${localStorageSize()}kb`);
</script>

<template>
    <!-- <main class="overflow-y-hidden">
        <div
            class="absolute inset-0 bg-[url(assets/img/grid.svg)] bg-center [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.1))]"
        ></div>

        <RouterView />
    </main> -->
    <div class="overflow-y-hidden">
        <RouterView />
    </div>
</template>
