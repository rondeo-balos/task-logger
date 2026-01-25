<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <GuestLayout>
        <Head title="Sign in" />

        <form @submit.prevent="submit" class="gap-4 flex flex-col items-center">
            <h2 class="text-xl font-black">Sign in to your account</h2>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>
            <InputError class="mt-2" :message="form.errors.email" />
            <InputError class="mt-2" :message="form.errors.password" />

            <div class="border rounded-xl divide-y divide-slate-300 w-full overflow-hidden shadow">
                <input type="text" v-model="form.email" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Email address" required />
                <input type="password" v-model="form.password" class="p-3 px-5 bg-transparent border-0 ring-0 focus:ring-0 w-full" placeholder="Password" required />
            </div>

            <div class="flex flex-row justify-between w-full">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 cursor-pointer">Remember me on this device</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Forgot your password?
                </Link>
            </div>

            <PrimaryButton class="w-full justify-center py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Sign in</PrimaryButton>
            <Link :href="route('register')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Register
            </Link>
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