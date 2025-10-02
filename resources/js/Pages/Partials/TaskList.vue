<script setup>
import Task from './Task.vue';
import { FormatElapsedTime, WeekRange } from './Composables/Time';
import { GetDayName } from './Composables/Time';
import Filter from './Filter.vue';
import Offcanvas from '@/Components/Offcanvas.vue';
import RichtextEditor from './RichtextEditor.vue';
import { PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/solid';
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps([ 'tasks', 'tags', 'total' ]);

watch(
    () => props.tasks,
    () => {
        if (ocOpen.value && activeTaskId.value) syncActiveFromProps();
    },
    { deep: true }
);

const emit = defineEmits([ 'resumeTask' ]);
const handleResume = (title) => {
    emit( 'resumeTask', title );
}

// ---- Single offcanvas/editor state ----
const ocOpen = ref(false);
const activeTaskId = ref(null);
const activeIndex = ref(null);

// local copy of descriptions while editing
const form = useForm({ description: [] });

function closeOffcanvas() {
    ocOpen.value = false;
}

function addDescription() {
    form.description.push('I worked with...');
    activeIndex.value = form.description.length - 1;
}

function removeDescription(i) {
    if (!confirm('Are you sure?')) return;
    form.description.splice(i, 1);
    if (activeIndex.value === i) activeIndex.value = null;
    if (form.description.length && activeIndex.value === null) activeIndex.value = 0;
}

function saveDescriptions() {
    if (!activeTaskId.value) return;
    form.post(route('tasks.update', [activeTaskId.value]), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({
                only: ['tasks'],
                onSuccess: () => {
                    // keep the panel open and show fresh data
                    syncActiveFromProps();
                }
            });
        }
    });
}

// no TypeScript, just JS
function toArray(v) {
    if (Array.isArray(v)) return v;
    if (v && typeof v === 'object') return Object.values(v);
    return [];
}

function findTaskInProps(id) {
    const weeks = toArray(props.tasks);
    for (const week of weeks) {
        const days = toArray(week);
        for (const day of days) {
            const tasksArr = toArray(day);
            const t = tasksArr.find(x => x && x.id === id);
            if (t) return t;
        }
    }
    return null;
}

function hydrateFromProps(id) {
    const t = findTaskInProps(id);
    form.description = Array.isArray(t?.description) ? [...t.description] : [];
    if (!form.description.length) activeIndex.value = null;
    else if (activeIndex.value == null || activeIndex.value >= form.description.length) activeIndex.value = 0;
}

function openDescriptions({ id }) {
    activeTaskId.value = id;
    hydrateFromProps(id);   // ← pull from props, not from the child
    ocOpen.value = true;
}

// keep this for after-reload live updates while the panel is open
function syncActiveFromProps() {
    if (!activeTaskId.value) return;
    hydrateFromProps(activeTaskId.value);
}
</script>

<template>

    <div class="flex flex-row items-end justify-between">
        <h1 class="text-center font-black text-gray-500 text-4xl">Total (Filtered): {{ FormatElapsedTime(total.monthly) }}</h1>
        <Filter />
    </div>

    <div class="flex flex-col-reverse text-white">

        <div v-for="(weekData, week) in tasks">

            <div class="p-2 py-1 flex flex-row items-center justify-between mt-8">
                <b>{{ WeekRange(2025, week).start.toDateString() }} - {{ WeekRange(2025, week).end.toDateString() }}</b>
                <span>Weekly Total: <b>{{ FormatElapsedTime(total.weekly[week]) }}</b></span>
            </div>

            <div class="flex flex-col-reverse">
                <div v-for="(dayData, dateKey) in weekData" class="my-3 border rounded-lg divide-y border-[var(--separator)] divide-[var(--separator)] shadow-xl">
                    <div class="p-2 bg-[var(--alt-bg)] flex flex-row items-center justify-between rounded-t-lg">
                        <b>{{ GetDayName(dateKey) }}</b>
                        <span>Total: <b>{{ FormatElapsedTime(total.daily[dateKey]) }}</b></span>
                    </div>
                    <div class="flex flex-col-reverse">
                        <div v-for="task in dayData" :key="task.id" class="first:border-0 first:rounded-b-lg border-b border-[var(--separator)] bg-[var(--card-bg)] hover:bg-[var(--header-bg)]">
                            <Task :task="task" :tags="tags" @resume-task="handleResume" @open-description="openDescriptions" />
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <Offcanvas v-model="ocOpen">
        <h2 class="text-2xl font-bold">Describe the work you performed</h2>
        <div class="py-4 space-y-2">
            <div v-for="(description, index) in form.description" :key="`description-${index}`">
                <div class="flex flex-row gap-2">
                    <RichtextEditor :key="`rte-${index}`" v-model="form.description[index]" @focusout="saveDescriptions" />
                    <!-- <textarea v-model="updateTask.description[index]" class="border rounded w-full bg-transparent" @focusout="handleUpdate"/> -->
                    <button type="button" @click="removeDescription(index)" class="p-2 px-3 text-red-600 hover:text-red-500 flex flex-row gap-1 ms-auto">
                        <TrashIcon class="size-6" />
                    </button>
                </div>
            </div>
            <button type="button" @click="addDescription" class="p-2 px-3 bg-blue-700 hover:bg-blue-600 flex flex-row gap-1">
                <PlusCircleIcon class="size-6"/> 
                Description
            </button>
        </div>
    </Offcanvas>

</template>