<script setup>
import { ArrowDownTrayIcon } from '@heroicons/vue/24/solid';
import { FormatElapsedTime, WeekRange, ParseDateTimeLocalToSeconds, FormatDateTime } from './Partials/Composables/Time';
import { GetDayName } from './Partials/Composables/Time';
import { Head } from '@inertiajs/vue3';

const props = defineProps([ 'tasks', 'tags', 'total' ]);

// Generate CSV content from tasks and download it
function generateCSV() {
    const {tasks, tags, total} = props;
    const rows = [];

    // Add header rows
    rows.push(['', '', '', '', 'This month\'s total:', FormatElapsedTime(total.monthly)]);

    // Loop through tasks to populate data
    for (const [week, weekData] of Object.entries(tasks)) {
        const weekRange = WeekRange(2025, week);
        rows.push([`${weekRange.start.toDateString()} - ${weekRange.end.toDateString()}`, '', '', '', 'Weekly Total', FormatElapsedTime(total.weekly[week])]);

        for (const [day, dayData] of Object.entries(weekData)) {
            rows.push(['', '', '', '', `${GetDayName(day)}:`, FormatElapsedTime(total.daily[day])]);
            rows.push(['Task', '', 'Type', 'From', 'To', 'Total']);

            for (const [i, task] of Object.entries(dayData)) {
                rows.push([
                    task.title,
                    'Task Description',
                    tags.find(tag => tag.id === task.tag)?.tag || '',
                    FormatDateTime(task.start * 1000),
                    FormatDateTime(task.end * 1000),
                    FormatElapsedTime(task.end - task.start),
                ]);

                if (task.description) {
                    for (const description of task.description) {
                        rows.push(['', description, '', '', '', '']);
                    }
                }
            }
        }
    }

    // Convert to CSV format
    const csvContent = rows.map(row => row.map(item => `"${item || ''}"`).join(',')).join('\n');

    // Trigger download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'tasks_report.csv';
    link.click();
}
</script>

<style scoped>
td, th {
    padding: 10px;
}
</style>

<template>
    <Head title="Export" />

    <div class="w-[1540px] mx-auto border rounded bg-gray-50 m-2">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th colspan="4"></th>
                    <th colspan="1">Total (filtered):</th>
                    <td colspan="1">{{ FormatElapsedTime(total.monthly) }}</td>
                </tr>
            </thead>
            <tbody class="divide-y">
                <template v-for="(weekData, week) in tasks">
                    <tr>
                        <!-- <th colspan="4">{{ WeekRange(2024, week).start.toDateString() }} - {{ WeekRange(2024, week).end.toDateString() }}</th> -->
                        <th colspan="4"></th>
                        <th colspan="1">Weekly Total:</th>
                        <td colspan="1">{{ FormatElapsedTime(total.weekly[week]) }}</td>
                    </tr>
                    <template v-for="(dayData, day) in weekData">
                        <tr class="bg-slate-200">
                            <th colspan="4"></th>
                            <th colspan="1">{{ GetDayName(day) }}:</th>
                            <td colspan="1">{{ FormatElapsedTime(total.daily[day]) }}</td>
                        </tr>
                        <tr>
                            <th>Task</th>
                            <th></th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Total</th>
                        </tr>
                        <template v-for="task in dayData">
                            <tr>
                                <td class="min-w-40">{{ task.title }}</td>
                                <td><b>Task Description</b></td>
                                <th>{{ tags.find(tag => tag.id === task.tag).tag }}</th>
                                <td class="text-nowrap">{{ FormatDateTime(task.start*1000) }}</td>
                                <td class="text-nowrap">{{ FormatDateTime(task.end*1000) }}</td>
                                <th>{{ FormatElapsedTime(task.end - task.start) }}</th>
                            </tr>
                            <tr v-for="(description, index) in task.description">
                                <td></td>
                                <td v-html="description"></td>
                                <td colspan="4"></td>
                            </tr>
                        </template>
                    </template>
                </template>
            </tbody>
        </table>
    </div>

    <!-- <button class="p-4 bg-blue-600 rounded-full border z-50 fixed bottom-10 right-10 text-white transition-transform hover:scale-125" @click="generateCSV">
        <ArrowDownTrayIcon class="size-7" />
    </button> -->
</template>