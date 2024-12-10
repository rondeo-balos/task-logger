<script setup>
import Task from './Task.vue';
import { FormatElapsedTime, WeekRange } from './Composables/Time';
import { GetDayName } from './Composables/Time';
import { reactive, ref } from 'vue';

const props = defineProps([ 'tasks' ]);

const total = reactive({});

// Calculate totals dynamically
const initializeTotals = () => {
    Object.keys(props.tasks).forEach(week => {
        Object.keys(props.tasks[week]).forEach(day => {
            total[day] = 0; // Initialize totals for each day
        });
    });
};
// Initialize totals when the component is mounted
initializeTotals();

// Update total for a specific day
const updateDayTotal = (day, duration) => {
    total[day] += duration;
};
</script>

<template>

    <div class="flex flex-col-reverse text-white">

        <div v-for="(weekData, week) in tasks">

            <div class="p-2 py-1 flex flex-row items-center justify-between mt-8">
                <b>{{ WeekRange(2024, week).start.toDateString() }} - {{ WeekRange(2024, week).end.toDateString() }}</b>
                <span>Weekly Total: <b>{{  }}</b></span>
            </div>
            
            <div v-for="(dayData, day) in weekData" class="my-3 border rounded-lg divide-y border-gray-600 divide-gray-600 overflow-hidden shadow-xl">
                <div class="p-2 bg-[#353a40] flex flex-row items-center justify-between">
                    <b>{{ GetDayName(day) }}</b>
                    <span>Total: <b>{{ FormatElapsedTime(total[day]) }}</b></span>
                </div>
                <div v-for="task in dayData">
                    <Task :task="task" v-model="total[day]" @update="updateDayTotal(day, $event)" />
                </div>
            </div>

        </div>

    </div>

</template>