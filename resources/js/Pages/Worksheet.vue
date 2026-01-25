<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from './Partials/Sidebar.vue';
import TaskForm from './Partials/TaskForm.vue';
import TaskList from './Partials/TaskList.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import { onMounted, ref } from 'vue';
import Collections from './Partials/Collections.vue';
import Tags from './Partials/Tags.vue';
import { PlusIcon } from '@heroicons/vue/24/solid';
import MasterSidebar from './Partials/MasterSidebar.vue';
import Queue from './Partials/Queue.vue';
import NotificationStack from './Partials/NotificationStack.vue';

const props = defineProps([ 'workplaces', 'tasks', 'tags', 'notes', 'total', 'status', 'resume_title', 'resume_board_id' ]);

const showCollectionCanvas = ref(false);
const showTagsCanvas = ref(false);
const showPending = ref(false);

const resumedTask = ref(null);
const handleResume = (title) => {
    resumedTask.value = { title };
};

onMounted(() => {
    if (props.resume_title) {
        resumedTask.value = { title: props.resume_title, boardId: props.resume_board_id ?? null };
    }
});
</script>

<template>
    <Head title="Time Tracker" />

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
                        <h2 class="text-2xl font-bold">Notes</h2>
                        <Collections :notes="notes" @resume-task="handleResume" />
                    </Offcanvas>

                    <Offcanvas v-model="showTagsCanvas">
                        <h2 class="text-2xl font-bold">Tags</h2>
                        <Tags :tags="tags" />
                    </Offcanvas>

                </div>
            </div>
        </div>

    </div>

    <NotificationStack :status="status" />

    
</template>
