<script setup>
import { PlusIcon, ArchiveBoxIcon, ArchiveBoxXMarkIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import WorkplaceCreation from './Partials/WorkplaceCreation.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps([ 'workplaces' ]);
const showCreation = ref(false);

const currentUserId = usePage().props.auth?.user?.id;

const isOwner = (workplace) => workplace.user_id === currentUserId;

const toggleArchive = (workplace) => {
    const routeName = workplace.archived_at ? 'workplace.unarchive' : 'workplace.archive';
    router.patch(route(routeName, workplace.id), {}, { preserveScroll: true });
};
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
                    <div v-for="workplace in workplaces" :key="workplace.id" class="group relative">
                        <Link :href="route('workplace.set', workplace.id)" class="block">
                            <div :class="[
                                'relative overflow-hidden rounded-2xl border border-[var(--separator)] bg-[var(--card-bg)] p-5 shadow-md transition transform group-hover:-translate-y-1 group-hover:shadow-xl',
                                workplace.archived_at ? 'opacity-60' : ''
                            ]">
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
                                    <span v-if="workplace.archived_at" class="text-xs px-2 py-1 rounded-full bg-gray-700 text-gray-200">Archived</span>
                                    <span v-else class="text-xs px-2 py-1 rounded-full bg-blue-900 text-blue-200">Active</span>
                                </div>
                            </div>
                        </Link>
                        <button
                            v-if="isOwner(workplace)"
                            type="button"
                            @click.stop.prevent="toggleArchive(workplace)"
                            :title="workplace.archived_at ? 'Unarchive' : 'Archive'"
                            class="absolute top-3 right-3 p-1.5 rounded-lg bg-black/40 text-gray-300 hover:text-white hover:bg-black/60 opacity-0 group-hover:opacity-100 transition"
                        >
                            <ArchiveBoxXMarkIcon v-if="workplace.archived_at" class="size-4" />
                            <ArchiveBoxIcon v-else class="size-4" />
                        </button>
                    </div>
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
