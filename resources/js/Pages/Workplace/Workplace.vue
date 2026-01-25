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
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link v-for="workplace in workplaces" :key="workplace.id" :href="route('workplace.set', workplace.id)" class="group">
                        <div class="relative overflow-hidden rounded-2xl border border-[var(--separator)] bg-[var(--card-bg)] p-5 shadow-md transition transform group-hover:-translate-y-1 group-hover:shadow-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-500 text-white flex items-center justify-center text-lg font-bold">
                                        {{ workplace.name?.[0] ?? 'W' }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white leading-tight">{{ workplace.name }}</h3>
                                        <p class="text-xs text-gray-400">Tap to enter</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-900 text-blue-200">Active</span>
                            </div>
                        </div>
                    </Link>
                    <button type="button" class="group relative overflow-hidden rounded-2xl border border-dashed border-blue-500 bg-[var(--body-bg)] p-5 shadow-inner transition hover:border-blue-400 hover:bg-[var(--card-bg)]" @click="showCreation = true">
                        <div class="flex items-center justify-center gap-3 text-blue-300 group-hover:text-white">
                            <span class="size-12 rounded-xl border border-blue-500 flex items-center justify-center">
                                <PlusIcon class="size-6" />
                            </span>
                            <div class="text-left">
                                <div class="text-lg font-semibold">Create workspace</div>
                                <div class="text-xs text-gray-400">Start a new space for your team</div>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <WorkplaceCreation v-model="showCreation" />
</template>
