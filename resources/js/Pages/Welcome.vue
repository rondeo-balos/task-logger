<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from './Partials/Sidebar.vue';
import TaskForm from './Partials/TaskForm.vue';
import TaskList from './Partials/TaskList.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import { ref } from 'vue';
import Collections from './Partials/Collections.vue';

const props = defineProps([ 'tasks', 'tags', 'notes', 'total' ]);

const showCanvas = ref(false);
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-[#1c1c1e] min-h-screen">
        <div class="flex flex-row content-evenly">
            
            <div class="w-64">
                <Sidebar v-model="showCanvas" />
            </div>
            
            <!-- Main content -->
            <div class="w-full p-5">
                <TaskForm :tags="tags" />
                <TaskList :tasks="tasks" :tags="tags" :total="total" />
            </div>

            <Offcanvas v-model="showCanvas">
                <h2 class="text-2xl font-bold">Collections</h2>
                <Collections :notes="notes" />
            </Offcanvas>

        </div>
    </div>
</template>
