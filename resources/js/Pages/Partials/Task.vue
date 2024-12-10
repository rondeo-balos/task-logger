<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps([ 'task' ]);

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
</script>

<template>
    <div class="flex flex-row hover:bg-[#32353a]">
        <input type="text" v-model="updateTask.title" class="bg-transparent border-0 ring-0 focus:ring-0 flex-grow" />
    </div>
</template>