<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateWorkplaceForm from './Partials/UpdateWorkplaceForm.vue';
import WorkplaceAccess from './Partials/WorkplaceAccess.vue';

defineProps([ 'workplace_id' ]);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Edit <u></u>
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdateWorkplaceForm class="max-w-xl" />
                </div>
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <WorkplaceAccess class="max-w-xl" :workplace_id="workplace_id" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>