<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="gap-4 flex flex-col items-center">
            <h2 class="text-xl font-black">Sign in to your account</h2>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>
            <InputError class="mt-2" :message="form.errors.name" />
            <InputError class="mt-2" :message="form.errors.email" />
            <InputError class="mt-2" :message="form.errors.password" />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />

            <div class="border rounded-xl divide-y divide-slate-300 w-full overflow-hidden shadow">
                <input type="text" v-model="form.name" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Name" required />
                <input type="text" v-model="form.email" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Email address" required />
                <input type="password" v-model="form.password" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Password" required />
                <input type="password" v-model="form.password_confirmation" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Confirm Password" required />
            </div>

            <PrimaryButton class="w-full justify-center py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Sign up</PrimaryButton>

            <div class="flex flex-row justify-center w-full">
                <Link :href="route('login')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Already registered?
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
/* Change autocomplete styles in WebKit */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus {
  transition: background-color 5000s ease-in-out 0s;
};
</style>