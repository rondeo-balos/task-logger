<script setup>
import Editor from '@hugerte/hugerte-vue';
import { CheckIcon } from '@heroicons/vue/24/solid';
import { ref, watch } from 'vue';

const value = defineModel();

// Define the emit for focusout event
const emit = defineEmits(['focusout']);

// Track if editor is dirty (has unsaved changes)
const isDirty = ref(false);
const originalValue = ref(value.value || '');

// Watch for changes in the editor value
watch(value, (newValue) => {
    isDirty.value = newValue !== originalValue.value;
});

// Handle the save button click
const handleSave = () => {
    originalValue.value = value.value;
    isDirty.value = false;
    emit('focusout');
};
</script>

<template>
    <div class="relative">
        <Editor 
            api-key="2cmhowp6xkrdpv31jc16axuj6suoxdty6m88p3wb5noc07s0"
            :init="{
                toolbar_mode: 'sliding',
                plugins: [
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons',
                    'link', 'lists', 'media', 'searchreplace', 'table',
                    'visualblocks', 'wordcount'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [
                    { value: 'First.Name', title: 'First Name' },
                    { value: 'Email', title: 'Email' },
                ],
                uploadcare_public_key: '430e377f9b0c0259468e'
            }"
            v-model="value"
        />
        <button 
            v-if="isDirty"
            type="button" 
            @click="handleSave" 
            class="p-2 px-3 bg-[#1c4ed7] hover:bg-[#2959de] flex flex-row gap-1 items-center self-end rounded-md text-white text-xs absolute top-2 right-2 z-10">
            <CheckIcon class="size-4 font-bold" />
            Save
        </button>
    </div>
</template>