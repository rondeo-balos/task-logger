<script setup>
import Modal from '@/Components/Modal.vue';
import { ListBulletIcon, PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/solid';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { FormatDateTime, ParseDateTimeLocalToSeconds, FormatElapsedTime } from './Composables/Time';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import TagSelector from '@/Components/TagSelector.vue';
import Offcanvas from '@/Components/Offcanvas.vue';

const props = defineProps([ 'task', 'tags' ]);
const id = props.task.id;

// Emit event to notify the parent of a time update
const emit = defineEmits(['total-update']);
// Calculate task duration and notify parent
const taskDuration = props.task.end - props.task.start;
emit('total-update', taskDuration);

const updateTask = useForm({
    title: props.task.title,
    description: props.task.description,
    tag: props.task.tag,
    start: props.task.start,
    end: props.task.end,
});

const handleUpdate = () => {
    updateTask.post( route('tasks.update', [id]), {
        onSuccess: page => {
            //router.reload();
            emit('total-update', (updateTask.end - updateTask.start) - taskDuration);
        }
    });
};

const handleDelete = () => {
    if( confirm('Are you sure?') ) {
        router.delete( route('tasks.delete', [id]), {
            onSuccess: page => {
                router.reload();
            }
        });
    }
}
const start = ref(FormatDateTime(props.task.start*1000));
const end = ref(FormatDateTime(props.task.end*1000));

const handleDateTimeUpdateStart = () => {
    updateTask.start = ParseDateTimeLocalToSeconds(start.value);
    handleUpdate();
};
const handleDateTimeUpdateEnd = () => {
    updateTask.end = ParseDateTimeLocalToSeconds(end.value);
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
    <div class="flex flex-row group items-center justify-between">
        <input type="text" v-model="updateTask.title" class="bg-transparent border-0 ring-0 focus:ring-0 flex-grow md:max-w-[40%]" @focusout="handleUpdate" />
        <button type="button" @click="openDescription = true">
            <ListBulletIcon class="size-6" />
        </button>
        
        <TagSelector :tags="tags" v-model="updateTask.tag" @change="handleUpdate" class="!w-36 text-center"/>
        <vue-tailwind-datepicker v-model="start" as-single @update:model-value="handleDateTimeUpdateStart" input-classes="bg-transparent border-1 rounded m-1 ring-0" class="max-w-[338px]" />
        <span class="max-w-10 text-center w-full">-</span>
        <vue-tailwind-datepicker v-model="end" as-single @update:model-value="handleDateTimeUpdateEnd" input-classes="bg-transparent border-1 rounded m-1 ring-0" class="max-w-[338px]" />
        <span class="mx-2 min-w-24 text-center font-bold text-[#81A1C1]">{{ FormatElapsedTime(updateTask.end - updateTask.start) }}</span>
        <button type="button" @click="handleDelete" class="p-2 px-3 text-red-500 hover:text-red-600 flex flex-row gap-1" ><TrashIcon class="size-6" /></button>
    </div>

    <Offcanvas v-model="openDescription">
        <h2 class="text-2xl font-bold">Describe the work you performed</h2>
        <div class="py-4 space-y-2">
            <div v-for="(description, index) in updateTask.description">
                <div class="flex flex-row gap-2">
                    <textarea v-model="updateTask.description[index]" class="border rounded w-full bg-transparent" @focusout="handleUpdate"/>
                    <button type="button" @click="removeDescription(index)" class="p-2 px-3 text-red-600 hover:text-red-500 flex flex-row gap-1 ms-auto" ><TrashIcon class="size-6" /></button>
                </div>
            </div>
            <button type="button" @click="newDescription" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1" ><PlusCircleIcon class="size-6" /> Description</button>
        </div>
    </Offcanvas>
</template>