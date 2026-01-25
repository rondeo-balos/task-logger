<script setup>
import { PlusIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import WorkplaceCreation from './Partials/WorkplaceCreation.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps([ 'workplaces' ]);
const showCreation = ref(false);
</script>

<template>
    <Head title="Workspaces" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Workspaces
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-row flex-wrap gap-3 p-3 justify-center">
                    <Link v-for="workplace in workplaces" :href="route('workplace.set', workplace.id)">
                        <img :src="`https://placehold.co/100x100/000000/ffffff/?text=${workplace.name[0]}`" :class="['size-24 rounded-xl']" />
                    </Link>
                </div>
                <div class="flex flex-row flex-wrap gap-3 p-3 justify-center">
                    <div class="size-24 rounded-xl bg-slate-300 flex items-center justify-center cursor-pointer" @click="showCreation = true">
                        <PlusIcon class="size-8 text-gray-600" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <WorkplaceCreation v-model="showCreation" />
</template>