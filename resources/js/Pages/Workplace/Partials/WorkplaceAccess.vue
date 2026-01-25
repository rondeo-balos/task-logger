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
const viewOther = ref(false);

const giveAccessForm = useForm({
    permission: [],
    email: ''
});

const revokeAccessForm = useForm({
    email: ''
});

const toggleViewOtherForm = useForm({
    email: '',
    permission: ['view-other']
});

const handleSubmit = () => {
    giveAccessForm.permission = [];
    if( read.value )
        giveAccessForm.permission.push( 'read' );
    if( write.value )
        giveAccessForm.permission.push( 'write' );
    if( viewOther.value )
        giveAccessForm.permission.push( 'view-other' );

    giveAccessForm.post( route('workplace.give', [props.workplace_id]), {
        preserveScroll: true,
        onFinish: () => {
            giveAccessForm.permission = [];
            read.value = false;
            write.value = false;
            viewOther.value = false;
        }
    });
};

const accessForUser = (user) => {
    const names = (user.permissions || []).map(p => p.name ?? p);
    const read = names.includes(`read ${props.workplace_id}`);
    const write = names.includes(`write ${props.workplace_id}`);
    const viewOther = names.includes(`view-other ${props.workplace_id}`);
    return { read, write, viewOther };
};

const revokeAccess = (user) => {
    if (!confirm(`Revoke all access for ${user.email}?`)) return;
    revokeAccessForm.email = user.email;
    revokeAccessForm.permission = ['read', 'write', 'view-other'];
    revokeAccessForm.post(route('workplace.revoke', [props.workplace_id]), {
        preserveScroll: true,
        onFinish: () => revokeAccessForm.reset()
    });
};

const toggleViewOther = (user, enabled) => {
    toggleViewOtherForm.email = user.email;
    toggleViewOtherForm.permission = ['view-other'];
    if (enabled) {
        toggleViewOtherForm.post(route('workplace.give', [props.workplace_id]), {
            preserveScroll: true,
            onFinish: () => toggleViewOtherForm.reset()
        });
    } else {
        toggleViewOtherForm.post(route('workplace.revoke', [props.workplace_id]), {
            preserveScroll: true,
            onFinish: () => toggleViewOtherForm.reset()
        });
    }
};
</script>

<template>
    <section class="space-y-16">
        <div>
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Give Access to workplace
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
                        <label class="flex items-center">
                            <Checkbox v-model:checked="viewOther" />
                            <span class="ms-2 text-sm text-gray-600 cursor-pointer">View others' tasks</span>
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
                            <th class="p-4">View others' tasks</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-gray-800 divide-gray-700">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-700">
                            <td class="p-4">{{ user.email }}</td>
                            <td class="p-4 space-x-2">
                                <span v-if="accessForUser(user).read" class="px-2 py-1 text-xs rounded bg-green-900 text-green-200">Read</span>
                                <span v-if="accessForUser(user).write" class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-200">Write</span>
                                <span v-if="accessForUser(user).viewOther" class="px-2 py-1 text-xs rounded bg-purple-900 text-purple-200">Others</span>
                                <span v-if="!accessForUser(user).read && !accessForUser(user).write && !accessForUser(user).viewOther" class="text-xs text-gray-500">No access</span>
                            </td>
                            <td class="p-4">
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        class="rounded border-gray-500 text-blue-600 bg-gray-800"
                                        :checked="accessForUser(user).viewOther"
                                        :disabled="toggleViewOtherForm.processing"
                                        @change="(e) => toggleViewOther(user, e.target.checked)"
                                    />
                                    <span>Allow</span>
                                </label>
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
