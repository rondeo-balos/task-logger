<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline';
import { PencilIcon, PlayIcon } from '@heroicons/vue/24/solid';
import { nextTick, ref, watchEffect } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps(['note']);
const parsedContent = ref(''); // Reactive variable to store parsed content
const urlTitles = ref({}); // Cache for URL titles

// Function to trim URL if it's too long
const trimUrl = (url) => {
    const maxUrlLength = 40;
    if (url.length > maxUrlLength) {
        return url.slice(0, maxUrlLength - 3) + "...";
    }
    return url;
};

// Function to fetch title for a URL
const getTitle = async (url) => {
    if (urlTitles.value[url]) {
        return urlTitles.value[url]; // Return cached title if available
    }

    try {
        const response = await fetch(`https://api.linkpreview.net/?key=0908fc79558e7d869684e61e4003b57e&q=${url}`);
        const data = await response.json();
        if (data && data.title) {
            urlTitles.value[url] = data.title; // Cache the title
            return data.title;
        } else {
            return trimUrl(url); // Fallback if no title is found
        }
    } catch (error) {
        console.error("Failed to fetch URL title:", error);
        return trimUrl(url); // Fallback if there's an error
    }
};

// Main function to parse content
const parseContent = async (content) => {
    const urlRegex = /(https?:\/\/[^\s]+)/g; // Regex to match all URLs
    const urls = content.match(urlRegex); // Extract all URLs from content

    if (!urls) return content; // If no URLs found, return the original content

    // Fetch titles for all URLs asynchronously
    const urlTitlePromises = urls.map(async (url) => {
        const title = await getTitle(url);
        return { url, title };
    });

    // Wait for all URL title promises to resolve
    const urlTitlesArray = await Promise.all(urlTitlePromises);

    // Replace URLs with titles in the content
    let updatedContent = content;
    for (const { url, title } of urlTitlesArray) {
        // Replace each URL with the title in the content
        updatedContent = updatedContent.replace(url, `<a href="${url}" target="_blank" rel="noopener noreferrer" style="color: #5a94f9;">${title}</a>`);
    }

    return updatedContent;
};

// Watch effect to reactively parse the content whenever 'notes' changes
watchEffect( async () => {
    if (props.note && props.note.content.length > 0) {
        parsedContent.value = await parseContent(props.note.content);
    }
});

const edit = ref(false);
const input = ref(null);

const startEdit = async () => {
    edit.value = true;
    await nextTick();
    input.value?.focus();
}

const emit = defineEmits(['total-update', 'resumeTask']);

const startTask = () => {
    emit( 'resumeTask', props.note.content );
    router.delete( route('notes.delete', [props.note.id]), {
        onSuccess: page => {
            router.reload();
        }
    });
};

const handleDelete = () => {
    if( confirm('Are you sure?') ) {
        router.delete( route('notes.delete', [props.note.id]), {
            onSuccess: page => {
                router.reload();
            }
        });
    }
}

const updateNote = useForm({
    content: props.note.content,
});

const handleUpdate = () => {
    edit.value = false;
    updateNote.post( route('notes.update', [props.note.id]), {
        onSuccess: page => {
            //router.reload();
        }
    });
};
</script>

<template>
    <div class="group flex flex-row items-center p-1 gap-2 hover:bg-[#0003]">
        <span v-html="parsedContent" class="w-full" v-if="!edit" />
        <form class="w-full">
            <input type="text" class="bg-transparent p-2 w-full border-0 focus:ring-0 ring-0" tabindex="0" ref="input" v-if="edit" v-model="updateNote.content" @focusout="handleUpdate">
        </form>
        <button class="p-2 hover:bg-white hover:text-black border rounded-md" @click="startTask" v-if="!edit">
            <PlayIcon class="size-3" />
        </button>
        <button class="p-2 hover:bg-white hover:text-black border rounded-md" @click="startEdit" v-if="!edit">
            <PencilIcon class="size-3" />
        </button>
        <button class="p-2 hover:bg-white hover:text-black border rounded-md" @click="handleDelete">
            <TrashIcon class="size-3" />
        </button>
    </div>
</template>