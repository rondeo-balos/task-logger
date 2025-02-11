<script setup>
import { nextTick, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";

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
    <div class="flex flex-row items-center justify-end mt-5 gap-3 text-gray-400">
        <div class="w-[250px]">
            <label>Filter User</label>
            <select class="bg-transparent border-1 rounded m-1 ring-0 block w-full" v-model="selectedUser" @change="onChange">
                <option></option>
                <option v-for="user in users" :value="user.id" >
                    {{ user.name }}
                </option>
            </select>
        </div>
        <div class="w-[400px]">
            <label>Filter Date</label>
            <vue-tailwind-datepicker @update:model-value="onChange" v-model:model-value="range" as-single use-range input-classes="bg-transparent border-1 rounded m-1 ring-0" class="max-w-[500px]" />
        </div>
    </div>
</template>

