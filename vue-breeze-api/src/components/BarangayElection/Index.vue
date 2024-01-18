<template>
    <div class="relative bg-gray-50 w-screen h-screen pattern">
        <nav
            class="z-20 flex shrink-0 grow-0 justify-around gap-4 border-t border-gray-200 bg-white/50 p-2.5 shadow-lg backdrop-blur-lg fixed top-2/4 -translate-y-2/4 left-6 min-h-[auto] min-w-[64px] flex-col rounded-lg border"
        >
            <a
                href="#profile"
                class="flex aspect-square min-h-[32px] w-16 flex-col items-center justify-center gap-1 rounded-md p-1.5 hover:bg-indigo-50 text-indigo-600"
            >
                <!-- HeroIcon - User -->
                <mdicon name="map" width="24" />

                <small class="text-center text-xs font-medium">City </small>
            </a>
            <a
                href="#profile"
                class="flex aspect-square min-h-[32px] w-16 flex-col items-center justify-center gap-1 rounded-md p-1.5 hover:bg-indigo-50 text-indigo-600"
            >
                <!-- HeroIcon - User -->
                <mdicon name="map-marker-circle" width="24" />

                <small class="text-center text-xs font-medium">Barangay</small>
            </a>
        </nav>
    </div>
</template>

<script setup></script>
