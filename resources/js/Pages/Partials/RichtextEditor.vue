<script setup>
import Editor from '@hugerte/hugerte-vue';

const value = defineModel();
</script>

<template>
    <Editor api-key="2cmhowp6xkrdpv31jc16axuj6suoxdty6m88p3wb5noc07s0"
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
</template>