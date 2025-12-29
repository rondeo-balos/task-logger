<script setup>
import { ref } from 'vue';

const props = defineProps([ 'tags' ]);
const emit = defineEmits([ 'change' ]);
const model = defineModel();
const focused = ref(false);

const getTagById = (id) => {
    return props.tags.find(tag => tag.id === id);
};

const handleOut = () => {
    setTimeout(() => focused.value = false, 200);
};

const handleClick = (id) => {
    model.value = id;
    emit('change');
};
</script>

<template>
    <div class="relative group z-50" @focusin="focused = true" @focusout="handleOut">
        <button v-if="getTagById(model)" type="button" class="rounded text-sm px-2" :style="`border: solid 1px var(--${getTagById(model).color}); color: var(--${getTagById(model).color});`">{{ getTagById(model).tag }}</button>
        <div v-show="focused" class="z-50 flex absolute top-full mt-2 left-0 w-72 max-w-96 border-[var(--separator)] p-4 bg-[var(--card-bg)] shadow-2xl rounded-lg border flex-wrap">
            <template v-for="tag in tags">
                <button type="button" class="border rounded m-1 cursor-pointer bg-transparent px-2" :style="`border: solid 1px var(--${tag.color}); ${model === tag.id ? `background: var(--${tag.color}); color: white;` : `color: var(--${tag.color});`}`" @click="handleClick(tag.id)">
                    {{ tag.tag }}
                </button>
            </template>
        </div>
    </div>
</template>