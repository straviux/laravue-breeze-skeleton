<script setup></script>
<template>
    <div
        class="relative z-20 bg-white rounded-2xl p-2 mx-auto w-full md:w-2/5 lg:1/3"
    >
        <h1 class="mt-2 ml-4">Filter results</h1>

        <!-- District -->
        <div class="main flex border rounded overflow-hidden m-4">
            <div
                class="title py-3 my-auto px-5 bg-indigo-500 text-white text-sm font-semibold mr-3"
            >
                District
            </div>
            <div class="flex items-center justify-center">
                <label class="flex p-2 cursor-pointer ml-3">
                    <input class="my-auto radio" type="radio" name="sfg" />
                    <div class="title px-2">1st</div>
                </label>

                <label class="flex p-2 cursor-pointer">
                    <input class="my-auto radio" type="radio" name="sfg" />
                    <div class="title px-2">2nd</div>
                </label>

                <label class="flex p-2 cursor-pointer">
                    <input class="my-auto radio" type="radio" name="sfg" />
                    <div class="title px-2">3rd</div>
                </label>

                <label class="flex p-2 cursor-pointer">
                    <input
                        class="my-auto radio"
                        type="radio"
                        name="sfg"
                        checked
                    />
                    <div class="title px-2">All</div>
                </label>
            </div>
        </div>
        <!-- End Disctrict -->
        <div class="flex py-6">
            <button class="btn mx-auto rounded">Next</button>
        </div>
    </div>
</template>
