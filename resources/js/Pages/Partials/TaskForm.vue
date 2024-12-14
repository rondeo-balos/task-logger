<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ListBulletIcon, PlayIcon, PlusCircleIcon, StopIcon, TagIcon } from '@heroicons/vue/24/solid';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import { FormatElapsedTime } from './Composables/Time';
import Modal from '@/Components/Modal.vue';
import TagSelector from '@/Components/TagSelector.vue';

const emit = defineEmits(['reload']);

defineProps([ 'tags' ]);

const newTasks = useForm({
    title: '',
    description: [],
    tag: 8,
    start: 0,
    end: 0,
});

const timer = ref('00:00:00');
const timerInterval = ref(null);
const isStarting = ref(false);

const startTask = () => {
    isStarting.value = true;
    newTasks.start = Math.floor( Date.now()/1000 );

    timerInterval.value = setInterval(() => {
        const elapsedTime = Math.floor( Date.now()/1000 ) - Math.floor(newTasks.start);
        timer.value = FormatElapsedTime(elapsedTime);
    }, 1000);
};

const submitTask = () => {
    clearInterval(timerInterval.value);
    isStarting.value = false;
    newTasks.end = Math.floor( Date.now()/1000 );
    timer.value = '00:00:00';

    newTasks.post( route('tasks.create'), {
        onSuccess: page => {
            newTasks.reset();
            emit('reload');
        }
    });
};

/**
 * For description modal
 */
 const openDescription = ref(false);

const newDescription = () => {
    newTasks.description.push([]);
};

const removeDescription = (index) => {
    if( confirm('Are you sure?') ) {
        newTasks.description.splice(index, 1);
        handleUpdate();
    }
};
</script>

<template>
    <div class="border border-gray-600 mb-5 bg-[#27272f] p-2 text-white shadow-xl sticky top-0 z-[100]">
        <form @submit.prevent="submitTask" class="flex flex-row justify-between items-center gap-5">
            <div class="flex-grow">
                <input type="text" v-model="newTasks.title" class="bg-transparent p-2 w-full border-0 focus:ring-0 ring-0" placeholder="What are you working on?" required />
            </div>
            <button type="button" @click="openDescription = true">
                <ListBulletIcon class="size-6" />
            </button>
            <TagSelector :tags="tags" v-model="newTasks.tag"/>
            <span class="font-extrabold px-5">{{ timer }}</span>
            <button v-show="!isStarting" type="button" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1" @click="startTask"><PlayIcon class="size-6" /> Start</button>
            <button v-show="isStarting" type="submit" class="p-2 px-3 bg-red-700 hover:bg-red-600 flex flex-row gap-1"><StopIcon class="size-6" /> Stop</button>
        </form>
    </div>

    <Modal :show="openDescription" @close="openDescription = false">
        <div class="divide-y divide-gray-400">
            <h2 class="text-lg font-medium p-6" >
                What did you do?
            </h2>

            <div class="p-4 space-y-2">
                <div v-for="(description, index) in newTasks.description">
                    <div class="flex flex-row gap-2">
                        <textarea v-model="newTasks.description[index]" class="border rounded w-full bg-transparent" />
                        <button type="button" @click="removeDescription(index)" class="p-2 px-3 text-red-600 hover:text-red-500 flex flex-row gap-1 ms-auto" ><TrashIcon class="size-6" /></button>
                    </div>
                </div>
                <button type="button" @click="newDescription" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1 ms-auto" ><PlusCircleIcon class="size-6" /> Description</button>
            </div>
        </div>
    </Modal>
</template>