<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from './Partials/Sidebar.vue';
import TaskForm from './Partials/TaskForm.vue';
import TaskList from './Partials/TaskList.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import { ref } from 'vue';
import Collections from './Partials/Collections.vue';
import { PlusIcon } from '@heroicons/vue/24/solid';
import MasterSidebar from './Partials/MasterSidebar.vue';
import Queue from './Partials/Queue.vue';

const props = defineProps([ 'workplaces', 'tasks', 'tags', 'notes', 'total' ]);

const showCollectionCanvas = ref(false);
const showTagsCanvas = ref(false);
const showPending = ref(false);

const resumedTask = ref('');
const handleResume = (title) => {
    // Call an event to TaskForm
    resumedTask.value = title;
}
</script>

<template>
    <Head title="Welcome" />

    <div class="w-screen min-h-screen max-h-screen bg-[var(--master-bg)] flex flex-row">

        <MasterSidebar :workplaces="workplaces" />

        <div class="max-h-full w-full relative">
            <div class="bg-[var(--body-bg)] w-full h-full max-h-full overflow-hidden overflow-y-auto">
                <div class="flex flex-row content-evenly min-h-full">
                    
                    <div class="w-64">
                        <Sidebar v-model:collection="showCollectionCanvas" v-model:tag="showTagsCanvas" v-model:pending="showPending" />
                    </div>
                    
                    <!-- Main content -->
                    <div class="w-full p-5">
                        <TaskForm :tags="tags" :resumedTask="resumedTask" />
                        <TaskList :tasks="tasks" :tags="tags" :total="total" @resume-task="handleResume" />
                    </div>

                    <Offcanvas v-model="showPending">
                        <h2 class="text-2xl font-bold">Pending Tasks</h2>
                        <Queue />
                    </Offcanvas>

                    <Offcanvas v-model="showCollectionCanvas">
                        <h2 class="text-2xl font-bold">Collections</h2>
                        <Collections :notes="notes" />
                    </Offcanvas>

                    <Offcanvas v-model="showTagsCanvas">
                        <h2 class="text-2xl font-bold">Tags</h2>

                    </Offcanvas>

                </div>
            </div>
        </div>

    </div>

    
</template>
