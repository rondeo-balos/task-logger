<script setup>
import { ref } from 'vue';

const statusMap = {
    200: { message: "Success", bg: "#28a745", color: "#ffffff" },         // Green with white text
    201: { message: "Created", bg: "#17a2b8", color: "#ffffff" },    // Teal with white text
    204: { message: "No Content", bg: "#6c757d", color: "#ffffff" }, // Gray with white text
    400: { message: "Bad Request", bg: "#ffc107", color: "#000000" }, // Yellow with black text
    401: { message: "Unauthorized", bg: "#fd7e14", color: "#ffffff" }, // Orange with white text
    403: { message: "Forbidden", bg: "#dc3545", color: "#ffffff" }, // Red with white text
    404: { message: "Not Found", bg: "#6c757d", color: "#ffffff" }, // Gray with white text
    409: { message: "Conflict", bg: "#6610f2", color: "#ffffff" },  // Purple with white text
    422: { message: "Unprocessable Entity", bg: "#e83e8c", color: "#ffffff" }, // Pink with white text
    500: { message: "Internal Server Error", bg: "#343a40", color: "#ffffff" }, // Dark Gray with white text
    502: { message: "Bad Gateway", bg: "#007bff", color: "#ffffff" }, // Blue with white text
    503: { message: "Service Unavailable", bg: "#fd7e14", color: "#ffffff" }, // Orange with white text
    504: { message: "Gateway Timeout", bg: "#6f42c1", color: "#ffffff" } // Dark Purple with white text
};

const messages = ref([]);

const processMessage = (code) => {
    if (!statusMap[code]) return;

    const { message, bg, color } = statusMap[code];

    const id = Date.now(); // Unique ID for each message
    messages.value.push({ id, message, bg, color });

    // Auto-remove after 3 seconds
    setTimeout(() => {
        messages.value = messages.value.filter(msg => msg.id !== id);
    }, 5000);
};

processMessage(400);
setTimeout(()=>processMessage(200), 1000);
setTimeout(()=>processMessage(201), 3000);
setTimeout(()=>processMessage(409), 7000);
</script>

<template>
    <div class="fixed right-10 bottom-10">
        <transition-group name="fade" tag="div" class="space-y-3">
            <div v-for="msg in messages" :key="msg.id" class="p-3 px-5 rounded-md shadow-lg min-w-52" :style="{ backgroundColor: msg.bg, color: msg.color }">
                {{ msg.message }}
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
/* Smooth fade-in and fade-out */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateX(20px);
}

/* Move animation for smooth reordering */
.fade-move {
    transition: transform 0.5s ease;
}
</style>

