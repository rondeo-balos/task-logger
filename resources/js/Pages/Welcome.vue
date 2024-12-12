<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from './Partials/Sidebar.vue';
import TaskForm from './Partials/TaskForm.vue';
import TaskList from './Partials/TaskList.vue';

const props = defineProps([ 'tasks', 'tags' ]);
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-[#212429] min-h-screen">
        <div class="flex flex-row content-evenly">
            
            <div class="w-64">
                <Sidebar />
            </div>
            
            <!-- Main content -->
            <div class="w-full p-5">
                <TaskForm :tags="tags" @reload="fetchData" />
                <TaskList :tasks="tasks" :tags="tags" />
            </div>

        </div>
    </div>
</template>
