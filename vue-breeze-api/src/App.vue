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
// console.log(`localstorage size: ${localStorageSize()}kb`);
</script>

<template>
    <!-- <main class="overflow-y-hidden">


        <RouterView />
    </main> -->
    <div>
        <RouterView />
    </div>
</template>
<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>
