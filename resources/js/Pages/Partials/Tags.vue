<script setup>
import { ref } from 'vue';
import SingleTag from './SingleTag.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps(['tags']);

const createTag = useForm({
    index: null,
    tag: '',
    color: 'secondary-emphasis'
});

const submitTag = () => {
    if(createTag.tag) {
        createTag.post( route('tags.create'), {
            onSuccess: page => {
                createTag.reset();
            }
        });
    }
};
</script>

<template>
    <ul class="flex flex-wrap items-center gap-5 mt-5">
        <li v-for="tag in tags">
            <SingleTag :tag="tag" />
        </li>
    </ul>
    <input type="text" class="bg-transparent p-2 w-full border-0 focus:ring-0 ring-0" placeholder="New Tag" v-model="createTag.tag" @focusout="submitTag" />
</template>