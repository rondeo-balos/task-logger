<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps([ 'workplace_id', 'users' ]);

const read = ref(false);
const write = ref(false);

const giveAccessForm = useForm({
    permission: [],
    email: ''
});

const revokeAccessForm = useForm({
    email: ''
});

const handleSubmit = () => {
    giveAccessForm.permission = [];
    if( read.value )
        giveAccessForm.permission.push( 'read' );
    if( write.value )
        giveAccessForm.permission.push( 'write' );

    giveAccessForm.post( route('workplace.give', [props.workplace_id]), {
        preserveScroll: true,
        onFinish: () => {
            giveAccessForm.permission = [];
        }
    });
};

const accessForUser = (user) => {
    const names = (user.permissions || []).map(p => p.name ?? p);
    const read = names.includes(`read ${props.workplace_id}`);
    const write = names.includes(`write ${props.workplace_id}`);
    return { read, write };
};

const revokeAccess = (user) => {
    if (!confirm(`Revoke all access for ${user.email}?`)) return;
    revokeAccessForm.email = user.email;
    revokeAccessForm.post(route('workplace.revoke', [props.workplace_id]), {
        preserveScroll: true,
        onFinish: () => revokeAccessForm.reset('email')
    });
};
</script>

<template>
    <section class="space-y-16">
        <div>
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Give Access to workspace
                </h2>
            </header>

            <form @submit.prevent="handleSubmit" class="w-full mt-6 space-y-6">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput v-model="giveAccessForm.email" id="email" type="text" class="mt-1 block w-full"/>
                    <InputError class="mt-2" :message="''" />
                </div>
                <div>
                    <div class="flex flex-row gap-8 w-full mt-2">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="read" />
                            <span class="ms-2 text-sm text-gray-600 cursor-pointer">Read</span>
                        </label>
                        <label class="flex items-center">
                            <Checkbox v-model:checked="write" />
                            <span class="ms-2 text-sm text-gray-600 cursor-pointer">Write</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="''" />
                </div>

                <PrimaryButton class="w-full justify-center py-3" :class="{ 'opacity-25': giveAccessForm.processing }" :disabled="giveAccessForm.processing">Submit</PrimaryButton>
            </form>
        </div>

        <div>
            <header>
                <h2 class="text-lg font-medium text-gray-900 mb-8">
                    Manage Access
                </h2>
            </header>

            <div class="shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y table-fixed divide-gray-700 text-gray-400">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="p-4">Email</th>
                            <th class="p-4">Access</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-gray-800 divide-gray-700">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-700">
                            <td class="p-4">{{ user.email }}</td>
                            <td class="p-4 space-x-2">
                                <span v-if="accessForUser(user).read" class="px-2 py-1 text-xs rounded bg-green-900 text-green-200">Read</span>
                                <span v-if="accessForUser(user).write" class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-200">Write</span>
                                <span v-if="!accessForUser(user).read && !accessForUser(user).write" class="text-xs text-gray-500">No access</span>
                            </td>
                            <td class="p-4">
                                <button type="button" class="text-sm text-red-400 hover:text-red-200" :disabled="revokeAccessForm.processing" @click="revokeAccess(user)">
                                    Revoke
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>
