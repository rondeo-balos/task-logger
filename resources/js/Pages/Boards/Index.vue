<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import MasterSidebar from '../Partials/MasterSidebar.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import NotificationStack from '../Partials/NotificationStack.vue';
import RichtextEditor from '../Partials/RichtextEditor.vue';
import { FormatElapsedTime } from '../Partials/Composables/Time';
import { PlusIcon, PlayIcon, PencilIcon, TrashIcon, PaperClipIcon } from '@heroicons/vue/24/solid';

const props = defineProps(['boards', 'users', 'statuses', 'workplaces', 'status']);

const showCreate = ref(false);
const showEdit = ref(false);
const activeBoard = ref(null);

const createForm = useForm({
    title: '',
    description: [''],
    status: props.statuses?.[0] ?? 'pending',
    due_date: '',
    attachments: [],
    assigned_users: []
});

const editForm = useForm({
    id: null,
    title: '',
    description: [''],
    status: 'pending',
    due_date: '',
    attachments: [],
    assigned_users: []
});
editForm.transform((data) => ({ ...data, _method: 'patch' }));

const statusForm = useForm({ status: '' });
const deleteForm = useForm({});

const groupedBoards = computed(() => {
    const groups = {};
    (props.statuses || []).forEach((status) => {
        groups[status] = [];
    });

    (props.boards || []).forEach((board) => {
        const key = groups[board.status] ? board.status : (props.statuses?.[0] ?? 'pending');
        groups[key].push(board);
    });

    return groups;
});

const statusLabel = (status) => status.replace('-', ' ');

const descriptionSnippet = (board) => {
    const raw = Array.isArray(board.description)
        ? board.description[0]
        : board.description;
    if (!raw) return '';
    return raw.replace(/<[^>]*>?/gm, '').slice(0, 140);
};

const formattedDate = (value) => {
    if (!value) return '';
    const date = new Date(value);
    return date.toLocaleDateString();
};

const handleCreateFiles = (event) => {
    createForm.attachments = event.target.files;
};

const handleEditFiles = (event) => {
    editForm.attachments = event.target.files;
};

const openCreate = () => {
    createForm.reset();
    createForm.status = props.statuses?.[0] ?? 'pending';
    createForm.description = [''];
    showCreate.value = true;
};

const submitCreate = () => {
    createForm.post(route('boards.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
            createForm.status = props.statuses?.[0] ?? 'pending';
            createForm.description = [''];
        }
    });
};

const openEdit = (board) => {
    activeBoard.value = board;
    editForm.id = board.id;
    editForm.title = board.title;
    editForm.description = Array.isArray(board.description) ? [...board.description] : [board.description || ''];
    editForm.status = board.status;
    editForm.due_date = board.due_date ? board.due_date.substring(0, 10) : '';
    editForm.assigned_users = (board.users || []).map((u) => u.id);
    editForm.attachments = [];
    showEdit.value = true;
};

const submitEdit = () => {
    if (!editForm.id) return;
    editForm.post(route('boards.update', editForm.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showEdit.value = false;
        }
    });
};

const updateStatus = (board, status) => {
    statusForm.status = status;
    statusForm.patch(route('boards.update', board.id), {
        preserveScroll: true,
    });
};

const deleteBoard = (board) => {
    if (!confirm(`Delete board "${board.title}"?`)) return;
    deleteForm.delete(route('boards.destroy', board.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Task Manager" />

    <div class="w-screen min-h-screen max-h-screen bg-[var(--master-bg)] flex flex-row">
        <MasterSidebar :workplaces="workplaces" />

        <div class="max-h-full w-full relative">
            <div class="bg-[var(--body-bg)] w-full h-full max-h-full overflow-y-auto">
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h1 class="text-3xl font-black text-white">Task Manager</h1>
                            <p class="text-gray-400 text-sm">Kanban view for boards with quick time-tracking access.</p>
                        </div>
                        <button type="button" class="flex items-center gap-2 px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white" @click="openCreate">
                            <PlusIcon class="size-5" /> New Board
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div v-for="status in statuses" :key="status" class="bg-[var(--card-bg)] border border-[var(--separator)] rounded-lg shadow-md flex flex-col">
                            <div class="flex items-center justify-between px-3 py-2 border-b border-[var(--separator)]">
                                <span class="font-semibold text-white capitalize">{{ statusLabel(status) }}</span>
                                <span class="text-xs text-gray-400">{{ groupedBoards[status]?.length || 0 }} boards</span>
                            </div>
                            <div class="p-3 space-y-3 overflow-y-auto max-h-[70vh]">
                                <div v-for="board in groupedBoards[status]" :key="board.id" class="bg-[var(--body-bg)] border border-[var(--separator)] rounded-md p-3 space-y-2 shadow-sm">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-semibold text-white">{{ board.title }}</h3>
                                            <p class="text-sm text-gray-400" v-if="descriptionSnippet(board)">{{ descriptionSnippet(board) }}</p>
                                            <p class="text-xs text-amber-300" v-if="board.due_date">Due: {{ formattedDate(board.due_date) }}</p>
                                        </div>
                                        <button type="button" class="text-gray-400 hover:text-white" @click="openEdit(board)">
                                            <PencilIcon class="size-5" />
                                        </button>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="user in board.users" :key="user.id" class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-100">
                                            {{ user.name }}
                                        </span>
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        Logged {{ FormatElapsedTime(board.total_logged || 0) }} • {{ board.tasks.length }} tasks
                                    </div>

                                    <div v-if="board.attachments?.length" class="text-xs text-gray-300 space-y-1">
                                        <div class="flex items-center gap-1 font-semibold text-gray-200"><PaperClipIcon class="size-4" /> Attachments</div>
                                        <ul class="space-y-1">
                                            <li v-for="file in board.attachments" :key="file.url">
                                                <a :href="file.url" target="_blank" class="hover:text-blue-400 truncate inline-flex items-center gap-1">
                                                    {{ file.name }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <select :value="board.status" class="bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-sm text-white" @change="(e) => updateStatus(board, e.target.value)">
                                            <option v-for="opt in statuses" :key="opt" :value="opt" class="bg-[var(--card-bg)] text-white">
                                                {{ statusLabel(opt) }}
                                            </option>
                                        </select>
                                        <Link :href="route('home', { resume_title: board.title, resume_board_id: board.id })" class="flex items-center gap-1 px-2 py-1 rounded bg-green-700 hover:bg-green-600 text-white text-sm">
                                            <PlayIcon class="size-4" /> Start task
                                        </Link>
                                        <button type="button" class="text-xs text-red-400 hover:text-red-200 ml-auto flex items-center gap-1" :disabled="deleteForm.processing" @click="deleteBoard(board)">
                                            <TrashIcon class="size-4" /> Delete
                                        </button>
                                    </div>
                                </div>
                                <p v-if="(groupedBoards[status]?.length || 0) === 0" class="text-sm text-gray-500">No boards yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Offcanvas v-model="showCreate">
        <h2 class="text-2xl font-bold mb-2">New Board</h2>
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-300">Title</label>
                <input v-model="createForm.title" type="text" class="w-full bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white" />
                <p class="text-xs text-red-400" v-if="createForm.errors.title">{{ createForm.errors.title }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-300">Description</label>
                <RichtextEditor v-model="createForm.description[0]" />
            </div>
            <div class="flex flex-wrap gap-3">
                <div>
                    <label class="text-sm text-gray-300">Status</label>
                    <select v-model="createForm.status" class="bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white">
                        <option v-for="status in statuses" :key="status" :value="status" class="bg-[var(--card-bg)] text-white">
                            {{ statusLabel(status) }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-300">Due date</label>
                    <input v-model="createForm.due_date" type="date" class="bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white" />
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-300">Assign users</label>
                <select multiple v-model="createForm.assigned_users" class="w-full bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white">
                    <option v-for="user in users" :key="user.id" :value="user.id" class="bg-[var(--card-bg)] text-white">
                        {{ user.name }} ({{ user.email }})
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-300">Attachments</label>
                <input type="file" multiple class="text-sm text-gray-300" @change="handleCreateFiles" />
            </div>
            <div class="flex justify-end">
                <button type="button" class="px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white" :disabled="createForm.processing" @click="submitCreate">
                    Create
                </button>
            </div>
        </div>
    </Offcanvas>

    <Offcanvas v-model="showEdit">
        <h2 class="text-2xl font-bold mb-2">Edit Board</h2>
        <div class="space-y-3" v-if="activeBoard">
            <div>
                <label class="text-sm text-gray-300">Title</label>
                <input v-model="editForm.title" type="text" class="w-full bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white" />
                <p class="text-xs text-red-400" v-if="editForm.errors.title">{{ editForm.errors.title }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-300">Description</label>
                <RichtextEditor v-model="editForm.description[0]" />
            </div>
            <div class="flex flex-wrap gap-3">
                <div>
                    <label class="text-sm text-gray-300">Status</label>
                    <select v-model="editForm.status" class="bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white">
                        <option v-for="status in statuses" :key="status" :value="status" class="bg-[var(--card-bg)] text-white">
                            {{ statusLabel(status) }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-300">Due date</label>
                    <input v-model="editForm.due_date" type="date" class="bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white" />
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-300">Assign users</label>
                <select multiple v-model="editForm.assigned_users" class="w-full bg-transparent border border-[var(--separator)] rounded px-2 py-1 text-white">
                    <option v-for="user in users" :key="user.id" :value="user.id" class="bg-[var(--card-bg)] text-white">
                        {{ user.name }} ({{ user.email }})
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-300">Add attachments</label>
                <input type="file" multiple class="text-sm text-gray-300" @change="handleEditFiles" />
            </div>
            <div class="flex justify-end">
                <button type="button" class="px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white" :disabled="editForm.processing" @click="submitEdit">
                    Save changes
                </button>
            </div>
        </div>
    </Offcanvas>

    <NotificationStack :status="status" />
</template>
