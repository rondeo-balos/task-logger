<script setup>
import { ArrowDownTrayIcon } from '@heroicons/vue/24/solid';
import { FormatElapsedTime, WeekRange, ParseDateTimeLocalToSeconds, FormatDateTime } from './Partials/Composables/Time';
import { GetDayName } from './Partials/Composables/Time';
import { Head } from '@inertiajs/vue3';
import html2canvas from 'html2canvas';
import { ref, computed } from 'vue';

const props = defineProps([ 'tasks', 'tags', 'total', 'filter' ]);

const captureArea = ref();

// Helper function to determine the correct year for a week number
const getYearForWeek = (weekNum) => {
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth(); // 0-11
    
    // If we're in December (month 11) and the week is 1-10, it's likely next year
    if (currentMonth === 11 && weekNum <= 10) {
        return currentYear + 1;
    }
    
    // If we're in January (month 0) and the week is > 50, it's likely last year
    if (currentMonth === 0 && weekNum > 50) {
        return currentYear - 1;
    }
    
    return currentYear;
};

// Computed property to sort days within each week by date (descending)
// AND reverse week order to match TaskList.vue's flex-col-reverse behavior
const sortedTasks = computed(() => {
    // Get all weeks and sort them by actual date (descending - newest first for table display)
    return Object.entries(props.tasks || {})
        .sort(([weekA], [weekB]) => {
            const yearA = getYearForWeek(parseInt(weekA));
            const yearB = getYearForWeek(parseInt(weekB));
            
            // Get actual start dates for accurate comparison
            const dateA = WeekRange(yearA, parseInt(weekA)).start;
            const dateB = WeekRange(yearB, parseInt(weekB)).start;
            
            // Sort by actual date (descending - newest first)
            return dateB - dateA;
        })
        .map(([weekNum, weekData]) => {
            // Sort days within each week
            const sortedDays = Object.entries(weekData)
                .sort(([dateA], [dateB]) => dateB.localeCompare(dateA)) // Sort dates descending
                .reduce((acc, [dateKey, dayData]) => {
                    acc[dateKey] = dayData;
                    return acc;
                }, {});
            
            return [weekNum, sortedDays];
        });
});

const takeScreenshot = async () => {
    // Capture element
    const canvas = await html2canvas(captureArea.value);

    // Convert to image
    const dataURL = canvas.toDataURL("image/png");

    // Create a link and trigger download
    const link = document.createElement("a");
    link.href = dataURL;
    link.download = "Worklog.png";
    link.click();
};
</script>

<style scoped>
td, th {
    padding: 10px;
}
</style>

<template>
    <Head title="Export" />

    <div class="max-w-[1540px] w-full mx-auto border rounded bg-gray-50 m-2" ref="captureArea">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th colspan="4">{{ new Date(props.filter.range[0]).toDateString() }} - {{ new Date(props.filter.range[1]).toDateString() }}</th>
                    <th colspan="1">Total (filtered):</th>
                    <td colspan="1">{{ FormatElapsedTime(total.monthly) }}</td>
                </tr>
            </thead>
            <tbody class="divide-y">
                <template v-for="[week, weekData] in sortedTasks">
                    <!-- <tr>
                        <th colspan="4">{{ WeekRange(getYearForWeek(week), week).start.toDateString() }} - {{ WeekRange(getYearForWeek(week), week).end.toDateString() }}</th>
                        <th colspan="4"></th>
                        <th colspan="1">Weekly Total:</th>
                        <td colspan="1">{{ FormatElapsedTime(total.weekly[week]) }}</td>
                    </tr> -->
                    <template v-for="(dayData, dateKey) in weekData">
                        <tr class="bg-slate-200">
                            <th colspan="4"></th>
                            <th colspan="1">{{ GetDayName(dateKey) }}:</th>
                            <td colspan="1">{{ FormatElapsedTime(total.daily[dateKey]) }}</td>
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

    <button class="p-4 bg-blue-600 rounded-full border z-50 fixed bottom-10 right-10 text-white transition-transform hover:scale-125" @click="takeScreenshot">
        <ArrowDownTrayIcon class="size-7" />
    </button>
</template>