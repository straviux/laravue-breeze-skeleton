<script setup></script>
<template>
    <div
        class="max-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12"
    >
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-purple-300 to-purple-400 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"
            ></div>
            <div
                class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20"
            >
                <div class="max-w-md mx-auto">
                    <div>
                        <h1 class="text-2xl font-semibold">Register</h1>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
                        >
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="username"
                                    name="username"
                                    type="text"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="username"
                                />
                                <label
                                    for="username"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Username</label
                                >
                            </div>
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="name"
                                />
                                <label
                                    for="name"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Fullname</label
                                >
                            </div>
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="email"
                                />
                                <label
                                    for="email"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Email Address</label
                                >
                            </div>
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="Password"
                                />
                                <label
                                    for="password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Password</label
                                >
                            </div>
                            <div class="relative">
                                <input
                                    autocomplete="off"
                                    id="confirm_password"
                                    name="confirm_password"
                                    type="password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="Confirm Password"
                                />
                                <label
                                    for="confirm_password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                                    >Confirm Password</label
                                >
                            </div>
                            <div class="relative">
                                <button
                                    class="hover:shadow hover:bg-blue-400 bg-blue-500 text-white rounded-md px-4 py-1 mt-4"
                                >
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
