<script setup>
import { useForm } from '@inertiajs/vue3';
import { ListBulletIcon, PlayIcon, StopIcon, TagIcon } from '@heroicons/vue/24/solid';
import { ref } from 'vue';
import { FormatElapsedTime } from './Composables/Time';

const newTasks = useForm({
    title: '',
    description: [],
    tag: '',
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

    newTasks.post( route('create'), {
        onSuccess: page => {

        }
    });
};
</script>

<template>
    <div class="border border-gray-600 mb-5 bg-[#2b3034] p-2 text-white">
        <form @submit.prevent="submitTask" class="flex flex-row justify-between items-center gap-5">
            <div class="flex-grow">
                <input type="text" v-model="newTasks.title" class="bg-[#212429] p-2 w-full" placeholder="What are you working on?" required />
            </div>
            <button type="button">
                <ListBulletIcon class="size-6" />
            </button>
            <button type="button" class="flex flex-row gap-2 text-gray-400 px-5">
                <TagIcon class="size-6" />
                Tag
            </button>
            <span class="font-extrabold px-5">{{ timer }}</span>
            <button v-show="!isStarting" type="button" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1" @click="startTask"><PlayIcon class="size-6" /> Start</button>
            <button v-show="isStarting" type="submit" class="p-2 px-3 bg-red-700 hover:bg-red-600 flex flex-row gap-1"><StopIcon class="size-6" /> Stop</button>
        </form>
    </div>
</template>