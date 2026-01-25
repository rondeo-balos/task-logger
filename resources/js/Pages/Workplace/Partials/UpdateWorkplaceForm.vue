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
    <section class="space-y-4">
        <header class="flex items-center gap-2">
            <div class="size-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                {{ (updateWorkplaceForm.name || 'W')[0] }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-white">
                    Workspace Information
                </h2>
                <p class="text-xs text-gray-400">Update the name shown to your team.</p>
            </div>
        </header>

        <form class="w-full space-y-4" @submit.prevent="handleSubmit">
            <div>
                <InputLabel for="name" value="Name" class="text-gray-300" />
                <TextInput v-model="updateWorkplaceForm.name" id="name" type="text" class="mt-2 block w-full bg-[var(--body-bg)] text-white" />
                <InputError class="mt-2" :message="updateWorkplaceForm.errors.name" />
            </div>
            <div class="flex items-center justify-end">
                <PrimaryButton type="submit" class="p-2 px-4 !bg-blue-700 hover:!bg-blue-600 flex flex-row gap-1" :class="{ 'opacity-25': updateWorkplaceForm.processing }" :disabled="updateWorkplaceForm.processing">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>
