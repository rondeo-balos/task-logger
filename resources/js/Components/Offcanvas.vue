<script setup>
import { onMounted, onUnmounted } from 'vue';


const show = defineModel();
const emit = defineEmits(['close']);

const close = () => {
    show.value = false;
    emit( 'close' );
};

const closeOnEscape = (e) => {
    if( e.key === 'Escape' ) {
        e.preventDefault();

        if( show.value ) {
            close();
        }
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.addEventListener('keydown', closeOnEscape));
</script>

<template>
    <div class="!z-[200] relative">
        <div v-show="show" class="fixed left-0 top-0 right-0 bottom-0 bg-black bg-opacity-60 backdrop-blur-sm" @click="close"></div>
        <Transition name="slide-fade">
            <div v-show="show" class="bg-[#1c1c1e] text-white fixed w-[500px] top-0 right-0 h-screen p-4 shadow-xl">
                <slot />
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.2s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(100px);
  opacity: 0;
}
</style>