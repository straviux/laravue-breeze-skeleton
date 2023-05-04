<template>
    <div class="flex h-screen bg-slate-700">
        <div
            class="card w-[95%] md:w-[50%] h-20 mx-auto mt-12 bg-gray-200 rounded-md shadow-lg"
        >
            <table class="table w-full">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Has Access?</th>
                        <th>Usage</th>
                        <th>Last Generated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr>
                        <td>1</td>
                        <td>Negros Oriental</td>
                        <td>City of Canlaon</td>
                        <td>Yes</td>
                        <td>2</td>
                        <td>2023/05/04</td>
                        <td>
                            <label class="cursor-pointer" for="my-modal">
                                <mdicon
                                    name="square-edit-outline"
                                    size="24"
                                    class="text-orange-500"
                                />
                            </label>
                        </td>
                    </tr>
                    <!-- row 2 -->
                    <tr>
                        <td>2</td>
                        <td>Cebu</td>
                        <td>Talisay</td>
                        <td>Yes</td>
                        <td>2</td>
                        <td>2023/05/04</td>
                        <td>
                            <label class="cursor-pointer" for="my-modal">
                                <mdicon
                                    name="square-edit-outline"
                                    size="24"
                                    class="text-orange-500"
                                />
                            </label>
                        </td>
                    </tr>
                    <!-- row 3 -->
                    <tr>
                        <td>3</td>
                        <td>Cebu</td>
                        <td>Argao</td>
                        <td>Yes</td>
                        <td>2</td>
                        <td>2023/05/04</td>
                        <td>
                            <label class="cursor-pointer" for="my-modal">
                                <mdicon
                                    name="square-edit-outline"
                                    size="24"
                                    class="text-orange-500"
                                />
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="checkbox" id="my-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative w-[350px]">
            <label
                for="my-modal"
                class="btn btn-sm btn-circle absolute right-3 top-2"
                >✕</label
            >
            <!-- <h3 class="font-bold text-lg">UPDATE FORM</h3> -->
            <form class="w-full max-w-lg mt-4">
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            for="province"
                        >
                            Province
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="province"
                            type="text"
                            value="Palawan"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            for="city"
                        >
                            City/Municipality
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="province"
                            type="text"
                            value="Puerto Princesa City"
                        />
                    </div>
                </div>
                <div class="form-control w-28">
                    <label class="label cursor-pointer">
                        <span
                            class="block uppercase tracking-wide text-gray-700 text-xs font-semibold mb-2"
                            >Has Access</span
                        >
                        <input type="checkbox" checked class="checkbox -mt-2" />
                    </label>
                </div>
            </form>
            <div class="modal-action">
                <label for="my-modal" class="btn">Update</label>
            </div>
        </div>
    </div>
</template>
