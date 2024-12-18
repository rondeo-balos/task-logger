<script setup>
const showCollectionCanvas = defineModel('collection');
const showTagsCanvas = defineModel('tag');
</script>

<template>
    <div class="flex flex-col px-3 text-white bg-[var(--sidebar-bg)] shadow-xl h-full">
        <div class="sticky top-0 py-3">
            <!--<a href="/" class="flex items-center mb-5">
                <img src="/logo.svg" class="me-2" width="50px" height="50px">
                <span class="font-bold -ms-5">Task Logger</span>
            </a>-->

            <ul class="flex flex-col gap-1">
                <li>
                    <a :href="route('home')" class="p-4 py-2 bg-blue-700 rounded-lg block">
                        Time Tracker
                    </a>
                </li>
                <li>
                    <a href="#" class="p-4 py-2 rounded-lg block" @click.prevent="showTagsCanvas = true">
                        Tags
                    </a>
                </li>
                <li>
                    <a href="#" class="p-4 py-2 bg-transparent rounded-lg block" @click.prevent="showCollectionCanvas = true">
                        Collections
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>