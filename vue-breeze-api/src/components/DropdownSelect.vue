<script setup></script>
<template>
    <div class="flex border rounded m-4 px-1">
        <div
            class="py-3 my-auto px-2 border-r-2 w-[120px] border-gray-300 text-gray-600 text-xs font-semibold"
        >
            Position
        </div>

        <div class="w-full dropdown dropdown-bottom">
            <label
                ref="dropDown"
                tabindex="0"
                class="btn bg-white btn-sm btn-block text-gray-500 m-1 hover:bg-base-100 border-0 text-[10px]"
                >Click to select</label
            >
            <ul
                tabindex="0"
                class="dropdown-content p-1 shadow bg-base-100 rounded-box w-full absolute z-[1000] min-w-max list-none overflow-y-auto h-[250px]"
            >
                <li>
                    <label
                        class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                    >
                        <input
                            type="checkbox"
                            class="checkbox checkbox-sm"
                            id="all"
                            v-model="allPositionSelected"
                            @change="toggleAllPosition"
                        />
                        <span class="label-text text-xs">All</span>
                    </label>
                </li>
                <li v-for="(position, index) in positions" :key="index">
                    <label
                        class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 hover:bg-gray-100 rounded-lg"
                    >
                        <input
                            type="checkbox"
                            class="checkbox checkbox-sm"
                            :id="position"
                            :value="position"
                            @change="resetPosition"
                            v-model="model.positions"
                        />
                        <span class="label-text text-xs">{{ position }}</span>
                    </label>
                </li>
            </ul>
        </div>
    </div>
</template>
