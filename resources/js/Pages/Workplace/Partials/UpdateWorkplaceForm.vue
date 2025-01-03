<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const updateWorkplaceForm = useForm({
    name: ''
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Workplace Information
            </h2>
        </header>

        <form class="w-full mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput v-model="updateWorkplaceForm.name"  id="name" type="text" class="mt-1 block w-full"/>
                <InputError class="mt-2" :message="''" />
            </div>
        </form>
    </section>
</template>
