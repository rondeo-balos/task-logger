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

const props = defineProps([ 'tasks', 'tags', 'notes', 'total' ]);

const showCollectionCanvas = ref(false);
const showTagsCanvas = ref(false);
</script>

<template>
    <Head title="Welcome" />

    <div class="w-screen min-h-screen max-h-screen bg-[#1c1c1e] flex flex-row">

        <MasterSidebar />

        <div class="max-h-full w-full relative">
            <div class="bg-[#1c1c1e] w-full max-h-full overflow-hidden overflow-y-auto">
                <div class="flex flex-row content-evenly">
                    
                    <div class="w-64">
                        <Sidebar v-model:collection="showCollectionCanvas" v-model:tag="showTagsCanvas" />
                    </div>
                    
                    <!-- Main content -->
                    <div class="w-full p-5">
                        <TaskForm :tags="tags" />
                        <TaskList :tasks="tasks" :tags="tags" :total="total" />
                    </div>

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
