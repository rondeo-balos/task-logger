<script setup>
import { nextTick, ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/solid";

const queryFilter = usePage().props.filter.range;
const queryUser = usePage().props.filter.user;

const users = usePage().props.users;
const range = ref(queryFilter ?? []);
const selectedUser = ref(queryUser ?? '');

const onChange = () => {
    nextTick(() => {
        router.get( route('home'), { range: range.value, user: selectedUser.value });
    });
};
</script>

<template>
    <div class="flex flex-row items-end justify-end mt-5 gap-3 text-gray-400">
        <div class="w-[250px]">
            <label>Filter User</label>
            <select class="bg-transparent border-1 rounded m-1 ring-0 block w-full" v-model="selectedUser" @change="onChange">
                <option></option>
                <option v-for="user in users" :value="user.id" >
                    {{ user.name }}
                </option>
            </select>
        </div>
        <div class="w-[450px]">
            <label>Filter Date</label>
            <vue-tailwind-datepicker @update:model-value="onChange" v-model:model-value="range" as-single use-range input-classes="bg-transparent border-1 rounded m-1 ring-0" class="max-w-[500px]" />
        </div>
        <div class="">
            <Link :href="route('export', { range, user: selectedUser })" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1 text-white rounded-sm m-1 h-[42px]">
                <ArrowDownTrayIcon class="size-5" />
            </Link>
        </div>
    </div>
</template>

