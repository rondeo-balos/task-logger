<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
    <div class="p-2 flex flex-col gap-2">
        <a :href="route('home')">
            <img src="/logo.svg" class="size-12 mb-2" />
        </a>

        <img src="https://placehold.co/100x100/?text=H" class="size-12 rounded-xl bg-[#141413] border-2 border-blue-700 scale-105" />
        <div class="size-12 rounded-xl bg-slate-300 border-2 border-transparent flex items-center justify-center">
            <PlusIcon class="size-8 text-gray-600" />
        </div>

        <a :href="route('profile.edit')" class="mt-auto">
            <img src="/profile-placeholder.png" class="size-12 rounded-full bg-[#141413]" />
        </a>
        
    </div>
</template>