<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/24/solid';
import DropdownLink from '@/Components/DropdownLink.vue';

const showCollectionCanvas = defineModel('collection');
const showTagsCanvas = defineModel('tag');
const showPending = defineModel('pending');

const currentWorkplace = ref(usePage().props.current_workplace);
</script>

<template>
    <div class="flex flex-col px-3 text-white bg-[var(--sidebar-bg)] shadow-xl h-full">
        <div class="sticky top-0 py-3">
            <!--<a href="/" class="flex items-center mb-5">
                <img src="/logo.svg" class="me-2" width="50px" height="50px">
                <span class="font-bold -ms-5">Task Logger</span>
            </a>-->

            <ul class="flex flex-col gap-1">
                <li class="hover:bg-gray-900 rounded-lg">
                    <Dropdown class="cursor-pointer" align="left" @is-open="(val) => isOpen = val" :isOpen="false">
                        <template #trigger>
                            <span class="text-xl font-bold p-4 py-2 flex flex-row justify-between items-center capitalize">{{ currentWorkplace.name }} <ChevronDownIcon :class="['size-4 transition-all', {'-scale-100' : isOpen}]" /> </span>
                        </template>
                        <template #content>
                            <ul class="divide-y divide-slate-500 w-full">
                                <li class="p-3 flex flex-row gap-2 items-center">
                                    <img :src="`https://placehold.co/100x100/000000/ffffff/?text=${currentWorkplace.name[0]}`" class="size-12 rounded-xl" /> <span class="font-bold">{{ currentWorkplace.name }}</span>
                                </li>
                                <li>
                                    <DropdownLink href="#" class="hover:bg-[#1264e3] p-2 px-4 w-full block">Analytics</DropdownLink>
                                </li>
                                <li>
                                    <DropdownLink :href="route('workplace.edit', [currentWorkplace.id])" class="hover:bg-[#1264e3] p-2 px-4 w-full block">Preferences</DropdownLink>
                                </li>
                            </ul>
                        </template>
                    </Dropdown>
                </li>
                <li>
                    <a :href="route('home')" class="p-4 py-2 bg-blue-700 rounded-lg block">
                        Time Tracker
                    </a>
                </li>
                <li>
                    <a :href="route('boards.index')" :class="['p-4 py-2 rounded-lg block', route().current('boards.index') ? 'bg-blue-700' : 'hover:bg-gray-900']">
                        Task Manager
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="p-4 py-2 rounded-lg block" @click.prevent="showPending = true">
                        Pending Tasks
                    </a>
                </li> -->
                <li>
                    <a href="#" class="p-4 py-2 rounded-lg block" @click.prevent="showTagsCanvas = true">
                        Tags
                    </a>
                </li>
                <li>
                    <a href="#" class="p-4 py-2 bg-transparent rounded-lg block" @click.prevent="showCollectionCanvas = true">
                        Notes
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
