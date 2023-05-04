<template>
    <div class="flex h-screen bg-slate-700">
        <div
            class="card w-[90%] md:w-[50%] h-20 mx-auto mt-12 bg-gray-200 rounded-md shadow-lg"
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
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr>
                        <th>1</th>
                        <td>Negros Oriental</td>
                        <td>City of Canlaon</td>
                        <td>
                            <label
                                class="cursor-pointer block w-full whitespace-nowrap bg-transparent items-center flex py-2 gap-2 px-2 rounded-lg"
                            >
                                <input
                                    type="checkbox"
                                    class="checkbox checkbox-sm checkbox-success"
                                />
                                <span class="label-text text-xs">Yes</span>
                            </label>
                        </td>
                        <td>2</td>
                        <td>2023/05/04</td>
                    </tr>
                    <!-- row 2 -->
                    <tr>
                        <th>2</th>
                        <td>Cebu</td>
                        <td>Talisay</td>
                        <td>Yes</td>
                        <td>2</td>
                        <td>2023/05/04</td>
                    </tr>
                    <!-- row 3 -->
                    <tr>
                        <th>3</th>
                        <td>Cebu</td>
                        <td>Argao</td>
                        <td>Yes</td>
                        <td>2</td>
                        <td>2023/05/04</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
