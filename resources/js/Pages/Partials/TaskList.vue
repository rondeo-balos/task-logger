<script setup>
import Task from './Task.vue';
import { FormatElapsedTime, WeekRange } from './Composables/Time';
import { GetDayName } from './Composables/Time';
import { reactive, ref, toRef } from 'vue';
import Filter from './Filter.vue';

const props = defineProps([ 'tasks', 'tags', 'total' ]);
</script>

<template>

    <h1 class="text-center font-black text-gray-500 text-4xl">This month's total: {{ FormatElapsedTime(total.monthly) }}</h1>

    <Filter />

    <div class="flex flex-col-reverse text-white">

        <div v-for="(weekData, week) in tasks">

            <div class="p-2 py-1 flex flex-row items-center justify-between mt-8">
                <b>{{ WeekRange(2024, week).start.toDateString() }} - {{ WeekRange(2024, week).end.toDateString() }}</b>
                <span>Weekly Total: <b>{{ FormatElapsedTime(total.weekly[week]) }}</b></span>
            </div>

            <div class="flex flex-col-reverse">
                <div v-for="(dayData, day) in weekData" class="my-3 border rounded-lg divide-y border-[var(--separator)] divide-[var(--separator)] shadow-xl">
                    <div class="p-2 bg-[var(--alt-bg)] flex flex-row items-center justify-between rounded-t-lg">
                        <b>{{ GetDayName(day) }}</b>
                        <span>Total: <b>{{ FormatElapsedTime(total.daily[day]) }}</b></span>
                    </div>
                    <div class="flex flex-col-reverse">
                        <div v-for="task in dayData" :key="task.id" class="first:border-0 first:rounded-b-lg border-b border-[var(--separator)] bg-[var(--card-bg)] hover:bg-[var(--header-bg)]">
                            <Task :task="task" :tags="tags" />
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</template>