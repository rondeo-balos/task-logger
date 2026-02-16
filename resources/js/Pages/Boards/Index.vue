<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import MasterSidebar from '../Partials/MasterSidebar.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import NotificationStack from '../Partials/NotificationStack.vue';
import RichtextEditor from '../Partials/RichtextEditor.vue';
import VueTailwindDatepicker from 'vue-tailwind-datepicker';
import Dropdown from '@/Components/Dropdown.vue';
import { FormatElapsedTime } from '../Partials/Composables/Time';
import { PlusIcon, PlayIcon, PencilIcon, TrashIcon, PaperClipIcon, TagIcon } from '@heroicons/vue/24/solid';
import Collections from '../Partials/Collections.vue';
import Tags from '../Partials/Tags.vue';
import Sidebar from '../Partials/Sidebar.vue';

const props = defineProps(['boards', 'users', 'statuses', 'workplaces', 'status', 'tags', 'notes']);
const hiddenStatuses = new Set(['archive']);
const visibleStatuses = computed(() => (props.statuses || []).filter((status) => !hiddenStatuses.has(status)));
const defaultBoardStatus = computed(() => visibleStatuses.value[0] ?? 'pending');

const showCollectionCanvas = ref(false);
const showTagsCanvas = ref(false);

const showCreate = ref(false);
const showEdit = ref(false);
const activeBoard = ref(null);

const createForm = useForm({
    title: '',
    description: [''],
    status: defaultBoardStatus.value,
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

const editTab = ref('details');

const statusForm = useForm({ status: '' });
const deleteForm = useForm({});

const localBoards = ref([...props.boards]);
watch(() => props.boards, (val) => {
    localBoards.value = [...val];
}, { deep: true });

const groupedBoards = computed(() => {
    const groups = {};
    visibleStatuses.value.forEach((status) => {
        groups[status] = [];
    });

    const fallbackStatus = visibleStatuses.value[0];
    (localBoards.value || []).forEach((board) => {
        if (hiddenStatuses.has(board.status)) return;

        if (groups[board.status]) {
            groups[board.status].push(board);
            return;
        }

        if (fallbackStatus) {
            groups[fallbackStatus].push(board);
        }
    });

    return groups;
});

const statusLabel = (status) => status.replace('-', ' ');

const userInitials = (user) => (user.name || user.email || '?').slice(0, 2).toUpperCase();

const gravatarUrl = (user) => {
    if (!user?.gravatar) return null;
    return `https://gravatar.com/avatar/${user.gravatar}?s=64&d=identicon`;
};

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
    createForm.status = defaultBoardStatus.value;
    createForm.description = [''];
    createForm.assigned_users = [];
    showCreate.value = true;
};

const submitCreate = () => {
    createForm.post(route('boards.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
            createForm.status = defaultBoardStatus.value;
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
    editTab.value = 'details';
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
    const target = localBoards.value.find((b) => b.id === board.id);
    if (target) target.status = status;
};

const deleteBoard = (board) => {
    if (!confirm(`Delete board "${board.title}"?`)) return;
    deleteForm.delete(route('boards.destroy', board.id), {
        preserveScroll: true,
    });
};

const toggleUserSelection = (form, userId) => {
    const list = form.assigned_users || [];
    const exists = list.includes(userId);
    form.assigned_users = exists ? list.filter((id) => id !== userId) : [...list, userId];
};

const formatTaskRange = (task) => {
    const start = task.start ? new Date(task.start * 1000) : null;
    const end = task.end ? new Date(task.end * 1000) : null;
    if (!start || !end) return '';
    const fmt = (d) => `${d.getMonth() + 1}/${d.getDate()} ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
    return `${fmt(start)} → ${fmt(end)}`;
};

const draggedBoardId = ref(null);
const handleDragStart = (boardId) => {
    draggedBoardId.value = boardId;
};
const handleDragOver = (event) => {
    event.preventDefault();
};
const handleDrop = (status) => {
    if (!draggedBoardId.value) return;
    const board = localBoards.value.find((b) => b.id === draggedBoardId.value);
    if (board && board.status !== status) {
        const prevStatus = board.status;
        updateStatus(board, status);
        board.status = status;
        // optimistic history entry so UI updates before reload
        const now = new Date().toISOString();
        board.history = [
            {
                id: `temp-${now}`,
                event: 'status_changed',
                payload: { from: prevStatus, to: status },
                created_at: now,
                user: null,
            },
            ...(board.history || []),
        ];
    }
    draggedBoardId.value = null;
};
</script>

<template>
    <Head title="Task Manager" />

    <div class="w-screen min-h-screen max-h-screen bg-[var(--master-bg)] flex flex-row">
        <MasterSidebar :workplaces="workplaces" />

        <div class="max-h-full w-full relative">
            <div class="bg-[var(--body-bg)] w-full h-full max-h-full overflow-y-auto">
                <div class="flex flex-row content-evenly min-h-full">
                    <div class="w-64">
                        <Sidebar v-model:collection="showCollectionCanvas" v-model:tag="showTagsCanvas" v-model:pending="showPending" />
                    </div>
                    <div class="p-6 space-y-6 w-full">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h1 class="text-3xl font-black text-white">Task Manager</h1>
                            <p class="text-gray-400 text-sm">Kanban view for boards with quick time-tracking access.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="flex items-center gap-2 px-3 py-2 rounded border border-[var(--separator)] text-white hover:border-blue-500" @click="showTagsCanvas = true">
                                <TagIcon class="size-4" /> Tags
                            </button>
                            <button type="button" class="flex items-center gap-2 px-3 py-2 rounded border border-[var(--separator)] text-white hover:border-blue-500" @click="showCollectionCanvas = true">
                                Notes
                            </button>
                            <button type="button" class="flex items-center gap-2 px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white" @click="openCreate">
                                <PlusIcon class="size-5" /> New Board
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div
                            v-for="status in visibleStatuses"
                            :key="status"
                            class="bg-[var(--card-bg)] border border-[var(--separator)] rounded-lg shadow-md flex flex-col"
                            @dragover="handleDragOver"
                            @drop="() => handleDrop(status)"
                        >
                            <div class="flex items-center justify-between px-3 py-2 border-b border-[var(--separator)]">
                                <span class="font-semibold text-white capitalize">{{ statusLabel(status) }}</span>
                                <span class="text-xs text-gray-400">{{ groupedBoards[status]?.length || 0 }} boards</span>
                            </div>
                            <div class="p-3 space-y-3 overflow-y-auto max-h-[70vh]">
                                <div
                                    v-for="board in groupedBoards[status]"
                                    :key="board.id"
                                    class="bg-[var(--body-bg)] border border-[var(--separator)] rounded-md p-3 space-y-2 shadow-sm"
                                    draggable="true"
                                    @dragstart="() => handleDragStart(board.id)"
                                >
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

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <div v-for="user in board.users" :key="user.id" class="flex items-center gap-1 text-xs text-gray-200">
                                            <img v-if="gravatarUrl(user)" :src="gravatarUrl(user)" class="size-6 rounded-full border border-[var(--separator)]" :title="user.name" />
                                            <span v-else class="size-6 rounded-full bg-blue-800 text-white flex items-center justify-center font-semibold">
                                                {{ userInitials(user) }}
                                            </span>
                                        </div>
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
                                        <Dropdown align="left" width="48">
                                            <template #trigger>
                                                <button type="button" class="flex items-center gap-2 px-2 py-1 rounded border border-[var(--separator)] bg-[var(--body-bg)] text-white text-sm hover:border-blue-500">
                                                    <span class="capitalize">{{ statusLabel(board.status) }}</span>
                                                    <TagIcon class="size-4 opacity-60" />
                                                </button>
                                            </template>
                                            <template #content>
                                                <div class="bg-[var(--card-bg)] text-white rounded-md shadow-lg border border-[var(--separator)]">
                                                    <button
                                                        v-for="opt in statuses"
                                                        :key="opt"
                                                        type="button"
                                                        class="w-full text-left px-3 py-2 text-sm hover:bg-blue-900/40"
                                                        @click="updateStatus(board, opt)"
                                                    >
                                                        {{ statusLabel(opt) }}
                                                    </button>
                                                </div>
                                            </template>
                                        </Dropdown>
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

                    <Offcanvas v-model="showCollectionCanvas">
                        <h2 class="text-2xl font-bold">Notes</h2>
                        <Collections :notes="notes" @resume-task="handleResume" />
                    </Offcanvas>

                    <Offcanvas v-model="showTagsCanvas">
                        <h2 class="text-2xl font-bold">Tags</h2>
                        <Tags :tags="tags" />
                    </Offcanvas>
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
                <div class="flex-1 min-w-[180px] space-y-1">
                    <label class="text-sm text-gray-300">Status</label>
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <button type="button" class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-lg border border-[var(--separator)] bg-[var(--body-bg)] text-white hover:border-blue-500">
                                <span class="text-sm capitalize">{{ statusLabel(createForm.status) }}</span>
                                <TagIcon class="size-4 opacity-60" />
                            </button>
                        </template>
                        <template #content>
                            <div class="bg-[var(--card-bg)] text-white rounded-md shadow-lg border border-[var(--separator)]">
                                <button
                                    v-for="status in statuses"
                                    :key="status"
                                    type="button"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-blue-900/40"
                                    @click="createForm.status = status"
                                >
                                    {{ statusLabel(status) }}
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="flex-1 min-w-[180px] space-y-1">
                    <label class="text-sm text-gray-300">Due date</label>
                    <VueTailwindDatepicker
                        as-single
                        v-model:model-value="createForm.due_date"
                        :formatter="{ date: 'YYYY-MM-DD', month: 'MMM' }"
                        input-classes="w-full bg-[var(--body-bg)] border border-[var(--separator)] rounded px-3 py-2 text-white placeholder:text-gray-500"
                        placeholder="Pick a date"
                    />
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm text-gray-300">
                    <span>Assign users</span>
                    <span class="text-xs text-gray-400">{{ createForm.assigned_users.length }} selected</span>
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    <button
                        v-for="user in users"
                        :key="user.id"
                        type="button"
                        @click="toggleUserSelection(createForm, user.id)"
                        :class="[
                            'flex items-center gap-2 w-full rounded-lg border px-2 py-2 text-left transition',
                            createForm.assigned_users.includes(user.id)
                                ? 'border-blue-500 bg-blue-900/40 text-white'
                                : 'border-[var(--separator)] bg-[var(--body-bg)] text-gray-200 hover:border-blue-700'
                        ]"
                    >
                        <img v-if="gravatarUrl(user)" :src="gravatarUrl(user)" class="size-6 rounded-full border border-[var(--separator)]" :title="user.name" />
                        <span v-else class="size-6 rounded-full bg-blue-800 text-white flex items-center justify-center font-semibold p-4">
                            {{ userInitials(user) }}
                        </span>
                        <span class="text-sm leading-tight">
                            <div class="font-semibold">{{ user.name }}</div>
                            <div class="text-[11px] text-gray-400">{{ user.email }}</div>
                        </span>
                    </button>
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-sm text-gray-300">Attachments</label>
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-[var(--separator)] bg-[var(--body-bg)] text-sm text-gray-300 hover:border-blue-500 cursor-pointer">
                    <PaperClipIcon class="size-4 opacity-70" />
                    <span>Select files</span>
                    <input type="file" multiple class="hidden" @change="handleCreateFiles" />
                </label>
                <p class="text-xs text-gray-500" v-if="createForm.attachments?.length">Selected: {{ createForm.attachments.length }} file(s)</p>
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
            <div class="flex items-center gap-3 border-b border-[var(--separator)] pb-2">
                <button type="button" :class="['text-sm px-3 py-1 rounded-full', editTab === 'details' ? 'bg-blue-700 text-white' : 'bg-[var(--card-bg)] text-gray-300']" @click="editTab = 'details'">
                    Details
                </button>
                <button type="button" :class="['text-sm px-3 py-1 rounded-full', editTab === 'logs' ? 'bg-blue-700 text-white' : 'bg-[var(--card-bg)] text-gray-300']" @click="editTab = 'logs'">
                    Logged tasks
                </button>
                <button type="button" :class="['text-sm px-3 py-1 rounded-full', editTab === 'history' ? 'bg-blue-700 text-white' : 'bg-[var(--card-bg)] text-gray-300']" @click="editTab = 'history'">
                    History
                </button>
            </div>

            <div v-if="editTab === 'details'" class="space-y-3">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[180px] space-y-1">
                    <label class="text-sm text-gray-300">Status</label>
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <button type="button" class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-lg border border-[var(--separator)] bg-[var(--body-bg)] text-white hover:border-blue-500">
                                <span class="text-sm capitalize">{{ statusLabel(editForm.status) }}</span>
                                <TagIcon class="size-4 opacity-60" />
                            </button>
                        </template>
                        <template #content>
                            <div class="bg-[var(--card-bg)] text-white rounded-md shadow-lg border border-[var(--separator)]">
                                <button
                                    v-for="status in statuses"
                                    :key="status"
                                    type="button"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-blue-900/40"
                                    @click="editForm.status = status"
                                >
                                    {{ statusLabel(status) }}
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="flex-1 min-w-[180px] space-y-1">
                    <label class="text-sm text-gray-300">Due date</label>
                    <VueTailwindDatepicker
                        as-single
                        v-model:model-value="editForm.due_date"
                        :formatter="{ date: 'YYYY-MM-DD', month: 'MMM' }"
                        input-classes="w-full bg-[var(--body-bg)] border border-[var(--separator)] rounded px-3 py-2 text-white placeholder:text-gray-500"
                        placeholder="Pick a date"
                    />
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm text-gray-300">
                    <span>Assign users</span>
                    <span class="text-xs text-gray-400">{{ editForm.assigned_users.length }} selected</span>
                    </div>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <button
                            v-for="user in users"
                            :key="user.id"
                            type="button"
                            @click="toggleUserSelection(editForm, user.id)"
                            :class="[
                                'flex items-center gap-2 w-full rounded-lg border px-2 py-2 text-left transition',
                                editForm.assigned_users.includes(user.id)
                                    ? 'border-blue-500 bg-blue-900/40 text-white'
                                    : 'border-[var(--separator)] bg-[var(--body-bg)] text-gray-200 hover:border-blue-700'
                            ]"
                        >
                            <img v-if="gravatarUrl(user)" :src="gravatarUrl(user)" class="size-6 rounded-full border border-[var(--separator)]" :title="user.name" />
                            <span v-else class="size-6 rounded-full bg-blue-800 text-white flex items-center justify-center font-semibold p-4">
                                {{ userInitials(user) }}
                            </span>
                            <span class="text-sm leading-tight">
                                <div class="font-semibold">{{ user.name }}</div>
                                <div class="text-[11px] text-gray-400">{{ user.email }}</div>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-sm text-gray-300">Add attachments</label>
                    <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-[var(--separator)] bg-[var(--body-bg)] text-sm text-gray-300 hover:border-blue-500 cursor-pointer">
                        <PaperClipIcon class="size-4 opacity-70" />
                        <span>Select files</span>
                        <input type="file" multiple class="hidden" @change="handleEditFiles" />
                    </label>
                    <p class="text-xs text-gray-500" v-if="editForm.attachments?.length">Selected: {{ editForm.attachments.length }} file(s)</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white" :disabled="editForm.processing" @click="submitEdit">
                        Save changes
                    </button>
                </div>
            </div>

            <div v-else-if="editTab === 'history'" class="space-y-3">
                <div v-if="activeBoard.history?.length" class="divide-y divide-[var(--separator)] border border-[var(--separator)] rounded-lg overflow-hidden">
                    <div v-for="(item, index) in activeBoard.history" :key="item.id || index" class="p-3 bg-[var(--body-bg)] text-sm text-gray-200">
                        <div class="flex items-start justify-between gap-2">
                            <div class="space-y-1">
                                <div class="font-semibold text-white capitalize">{{ item.event.replace('_', ' ') }}</div>
                                <div class="text-xs text-gray-400">
                                    <span v-if="item.payload?.from !== undefined">From: {{ item.payload.from }}</span>
                                    <span v-if="item.payload?.to !== undefined" class="ml-2">To: {{ item.payload.to }}</span>
                                    <span v-if="item.payload?.user_ids">Assignees: {{ item.payload.user_ids.join(', ') }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 text-right">
                                <div>{{ new Date(item.created_at).toLocaleString() }}</div>
                                <div v-if="item.user" class="flex items-center gap-1 justify-end mt-1">
                                    <img v-if="item.user.gravatar" :src="`https://gravatar.com/avatar/${item.user.gravatar}?s=32&d=identicon`" class="size-4 rounded-full" />
                                    <span>{{ item.user.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400">No history yet.</p>
            </div>

            <div v-else class="space-y-3">
                <div v-if="activeBoard.tasks?.length" class="divide-y divide-[var(--separator)] border border-[var(--separator)] rounded-lg overflow-hidden">
                    <div v-for="task in activeBoard.tasks" :key="task.id" class="p-3 bg-[var(--body-bg)] text-sm text-gray-200">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-semibold text-white">{{ task.title }}</div>
                                <div class="text-xs text-gray-400">{{ formatTaskRange(task) }}</div>
                            </div>
                            <div class="text-xs text-gray-300">
                                {{ FormatElapsedTime((task.end - task.start) || 0) }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1" v-if="task.user">
                            {{ task.user.name }}
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400">No logged tasks yet.</p>
            </div>
        </div>
    </Offcanvas>

    <Offcanvas v-model="showCollectionCanvas">
        <h2 class="text-2xl font-bold">Notes</h2>
        <Collections :notes="notes" />
    </Offcanvas>

    <Offcanvas v-model="showTagsCanvas">
        <h2 class="text-2xl font-bold">Tags</h2>
        <Tags :tags="tags" />
    </Offcanvas>

    <NotificationStack :status="status" />
</template>
