<script setup>
import { ref } from 'vue';
import SingleCollection from './SingleCollection.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps(['notes']);

const createNote = useForm({
    index: null,
    content: ''
});

const submitNote = () => {
    if(createNote.content) {
        createNote.post( route('notes.create'), {
            onSuccess: page => {
                createNote.reset();
            }
        });
    }
};
</script>

<template>
    <ul class="flex flex-col gap-1 mt-5">
        <li v-for="note in notes">
            <SingleCollection :note="note" />
        </li>
    </ul>
    <input type="text" class="bg-transparent p-2 w-full border-0 focus:ring-0 ring-0" placeholder="Type anything" v-model="createNote.content" @focusout="submitNote" />
</template>