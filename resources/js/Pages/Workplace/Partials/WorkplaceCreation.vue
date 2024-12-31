<script setup>
import Modal from '@/Components/Modal.vue';
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { useForm } from '@inertiajs/vue3';

const showCreation = defineModel();

const creationForm = useForm({
    name: ''
});

const handleSubmit = () => {
    creationForm.post( route('workplace.create'), {
        onSuccess: page => {}
    });
};
</script>

<template>
    <Modal :show="showCreation" @close="showCreation = false">
        <div class="divide-y divide-[var(--separator)]">
            <div class="p-4">
                <h2 class="text-2xl font-bold">Create new workplace</h2>
            </div>
            <form @submit.prevent="handleSubmit" class="p-4 flex flex-col gap-4 items-end">
                <input type="text" class="bg-transparent border-1 rounded ring-0 w-full" placeholder="Workplace Name" v-model="creationForm.name" required />
                <button type="submit" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1"><PlusCircleIcon class="size-6" /> Create</button>
            </form>
        </div>
    </Modal>
</template>