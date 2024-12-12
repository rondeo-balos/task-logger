<script setup>
import Modal from '@/Components/Modal.vue';
import { ListBulletIcon, PlusCircleIcon } from '@heroicons/vue/24/solid';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { FormatDateTime, ParseDateTimeLocalToSeconds } from './Composables/Time';

const props = defineProps([ 'task', 'tags' ]);

// Emit event to notify the parent of a time update
const emit = defineEmits(['update']);

// Calculate task duration and notify parent
const taskDuration = props.task.end - props.task.start;
emit('update', taskDuration);

const updateTask = useForm({
    title: props.task.title,
    description: props.task.description,
    tag: props.task.tag,
    start: props.task.start,
    end: props.task.end,
});

const handleUpdate = () => {
    updateTask.post( route('tasks.update', [props.task.id]), {
        onSuccess: page => {
            //router.reload();
        }
    });
};

const handleDelete = () => {
    if( confirm('Are you sure?') ) {
        router.delete( route('tasks.delete', [props.task.id]), {
            onSuccess: page => {
                router.reload();
            }
        });
    }
}

const start = ref(null);
const end = ref(null);
const handleDateTimeUpdate = (field, el) => {
    updateTask[field] = ParseDateTimeLocalToSeconds(el.value);
    handleUpdate();
};

/**
 * For description modal
 */
const openDescription = ref(false);

const newDescription = () => {
    updateTask.description.push([]);
};

const removeDescription = (index) => {
    if( confirm('Are you sure?') ) {
        updateTask.description.splice(index, 1);
        handleUpdate();
    }
};
</script>

<template>
    <div class="flex flex-row hover:bg-[#32353a] group items-center">
        <input type="text" v-model="updateTask.title" class="bg-transparent border-0 ring-0 focus:ring-0 flex-grow" @focusout="handleUpdate" />
        <button type="button" @click="openDescription = true">
            <ListBulletIcon class="size-6" />
        </button>
        <select class="bg-[#212429] group-hover:bg-[#32353a] border-0 cursor-pointer" v-model="updateTask.tag" @change="handleUpdate">
            <option disabled>Tag</option>
            <option v-for="tag in tags" :value="tag.id">{{ tag.tag }}</option>
        </select>
        <input type="datetime-local" step="1" @change="handleDateTimeUpdate('start', start)" ref="start" class="bg-transparent border-1 rounded m-1 ring-0" :value="FormatDateTime(updateTask.start*1000)">
        -
        <input type="datetime-local" step="1" @change="handleDateTimeUpdate('end', end)" ref="end" class="bg-transparent border-1 rounded m-1 ring-0" :value="FormatDateTime(updateTask.end*1000)">
        <button type="button" @click="handleDelete" class="p-2 px-3 text-red-600 hover:text-red-500 flex flex-row gap-1 ms-auto" ><TrashIcon class="size-6" /></button>
    </div>

    <Modal :show="openDescription" @close="openDescription = false">
        <div class="divide-y">
            <h2 class="text-lg font-medium p-6" >
                What did you do?
            </h2>

            <div class="p-4 space-y-2">
                <div v-for="(description, index) in updateTask.description">
                    <div class="flex flex-row gap-2">
                        <textarea v-model="updateTask.description[index]" class="border rounded w-full bg-transparent" @focusout="handleUpdate"/>
                        <button type="button" @click="removeDescription(index)" class="p-2 px-3 text-red-600 hover:text-red-500 flex flex-row gap-1 ms-auto" ><TrashIcon class="size-6" /></button>
                    </div>
                </div>
                <button type="button" @click="newDescription" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1 ms-auto" ><PlusCircleIcon class="size-6" /> Description</button>
            </div>
        </div>
    </Modal>
</template>