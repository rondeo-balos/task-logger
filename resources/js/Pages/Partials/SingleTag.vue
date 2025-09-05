<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline';
import { PencilIcon, CheckIcon } from '@heroicons/vue/24/solid';
import { nextTick, ref, watchEffect } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps(['tag', 'color']);

const edit = ref(false);
const input = ref(null);
const colori = ref(null);

const startEdit = async () => {
    edit.value = true;
    await nextTick();
    input.value?.focus();
}

const handleDelete = () => {
    if( confirm('Are you sure?') ) {
        router.delete( route('tags.delete', [props.tag.id]), {
            onSuccess: page => {
                router.reload();
            }
        });
    }
}

const updateTag = useForm({
    tag: props.tag.tag,
    color: props.tag.color
});

const handleUpdate = () => {
    edit.value = false;
    updateTag.post( route('tags.update', [props.tag.id]), {
        onSuccess: page => {
            //router.reload();
        }
    });
};

const colors = [
    'primary',
    'primary-emphasis',
    'secondary',
    'secondary-emphasis',
    'danger',
    'warning',
    'info',
    'success'
];
</script>

<template>
    <div class="group flex flex-row items-center p-1 gap-2 hover rounded-lg px-3" :style="`border: solid 1px var(--${updateTag.color});`">
        <span v-if="!edit">{{ updateTag.tag }}</span>
        <form class="w-full">
            <input type="text" class="bg-transparent p-2 border-0 focus:ring-0 ring-0" ref="input" v-if="edit" v-model="updateTag.tag">
            <select class="bg-transparent p-2 border-0 focus:ring-0 ring-0" :style="`color: var(--${updateTag.color});`" ref="colori" v-if="edit" v-model="updateTag.color">
                <option v-for="color in colors">{{ color }}</option>
            </select>
        </form>
        <button class="p-1 hover:bg-white hover:text-black rounded-md" @click="handleUpdate" v-if="edit">
            <CheckIcon class="size-3" />
        </button>
        <button class="p-1 hover:bg-white hover:text-black rounded-md" @click="startEdit" v-if="!edit">
            <PencilIcon class="size-3" />
        </button>
        <button class="p-1 hover:bg-white hover:text-black rounded-md" @click="handleDelete">
            <TrashIcon class="size-3" />
        </button>
    </div>
</template>