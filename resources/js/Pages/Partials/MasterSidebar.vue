<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import WorkplaceCreation from './WorkplaceCreation.vue';
import { ref } from 'vue';

defineProps([ 'workplaces' ]);
const showCreation = ref(false);
</script>

<template>
    <div class="p-2 flex flex-col gap-2">
        <Link :href="route('home')">
            <img src="/logo.svg" class="size-12 mb-2" />
        </Link>

        <Link v-for="workplace in workplaces" :href="route('workplace.set', workplace.id)">
            <img :src="`https://placehold.co/100x100/000000/ffffff/?text=${workplace.name[0]}`" :class="['size-12 rounded-xl border-2 border-transparent', {'!border-blue-700' : workplace.current}]" />
        </Link>
        <div class="size-12 rounded-xl bg-slate-300 border-2 border-transparent flex items-center justify-center cursor-pointer" @click="showCreation = true">
            <PlusIcon class="size-8 text-gray-600" />
        </div>

        <Link :href="route('profile.edit')" class="mt-auto">
            <img src="/profile-placeholder.png" class="size-12 rounded-full bg-[#141413]" />
        </Link>
    </div>

    <WorkplaceCreation v-model="showCreation" />
</template>