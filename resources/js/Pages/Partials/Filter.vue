<script setup>
import { nextTick, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";

const queryFilter = usePage().props.filter.range;
const range = ref(queryFilter ?? []);

const onChange = () => {
    nextTick(() => {
        router.get( route('home'), { range: range.value });
    });
};
</script>

<template>
    <div class="flex flex-row items-center justify-end mt-5 gap-3 text-gray-400">
        <label>Date Filter</label>
        <vue-tailwind-datepicker @update:model-value="onChange" v-model:model-value="range" as-single use-range input-classes="bg-transparent border-1 rounded m-1 ring-0" class="max-w-[500px]" />
    </div>
</template>

