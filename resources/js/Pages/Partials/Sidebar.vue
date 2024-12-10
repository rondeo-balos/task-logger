<template>
    <div class="flex flex-col p-3 text-white bg-[#2b3034] shadow-xl sticky top-0 h-screen">
        <a href="/" class="flex items-center mb-5">
            <img src="/logo.svg" class="me-2" width="50px" height="50px">
            <span class="font-bold -ms-5">Task Logger</span>
        </a>

        <ul class="flex flex-col gap-1">
            <li>
                <a :href="route('home')" class="p-4 py-2 bg-blue-600 rounded-lg block">
                    Time Tracker
                </a>
            </li>
            <li>
                <a href="#" class="p-4 py-2 rounded-lg block">
                    Tags
                </a>
            </li>
            <li>
                <a href="#" class="p-4 py-2 bg-transparent rounded-lg block">
                    Notes
                </a>
            </li>
        </ul>
    </div>
</template>