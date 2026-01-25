<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps([ 'workplace' ]);

const updateWorkplaceForm = useForm({
    name: props.workplace.name
});

const handleSubmit = () => {
    updateWorkplaceForm.patch( route('workplace.update', [props.workplace.id]), {
        preserveScroll: true
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Workspace Information
            </h2>
        </header>

            <form class="w-full mt-6 space-y-6" @submit.prevent="handleSubmit">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput v-model="updateWorkplaceForm.name"  id="name" type="text" class="mt-1 block w-full"/>
                    <InputError class="mt-2" :message="updateWorkplaceForm.errors.name" />
                </div>
                <PrimaryButton type="submit" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1" :class="{ 'opacity-25': updateWorkplaceForm.processing }" :disabled="updateWorkplaceForm.processing">Save</PrimaryButton>
            </form>
        </section>
    </template>
