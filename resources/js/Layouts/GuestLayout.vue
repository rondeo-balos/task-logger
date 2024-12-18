<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-row w-full min-h-screen items-center justify-center">
        <div class="border rounded-xl border-gray-200 p-8 gap-4 flex flex-col items-center min-w-96 max-w-[500px]">

            <div class="bg-[var(--card-bg)] p-2 rounded-xl">
                <Link href="/">
                    <img src="/logo.svg" class="size-10" />
                </Link>
            </div>

            <slot />

        </div>
    </div>
</template>
