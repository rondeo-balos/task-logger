<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateWorkplaceForm from './Partials/UpdateWorkplaceForm.vue';
import WorkplaceAccess from './Partials/WorkplaceAccess.vue';

defineProps([ 'workplace_id', 'workplace', 'users' ]);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Edit Workspace
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8">
                <div class="border border-[var(--separator)] bg-[var(--card-bg)] text-white p-4 shadow sm:rounded-2xl sm:p-8">
                    <UpdateWorkplaceForm class="max-w-xl" :workplace="workplace" />
                </div>
                <div class="border border-[var(--separator)] bg-[var(--card-bg)] text-white p-4 shadow sm:rounded-2xl sm:p-8">
                    <WorkplaceAccess class="max-w-xl" :workplace_id="workplace_id" :users="users" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
