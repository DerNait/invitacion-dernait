<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    message: { type: String, default: '' },
    type: { type: String, default: 'success' }, // success | error
});

const visible = ref(false);
let timer = null;

watch(
    () => props.message,
    (msg) => {
        if (!msg) return;
        visible.value = true;
        clearTimeout(timer);
        timer = setTimeout(() => (visible.value = false), 5000);
    },
    { immediate: true }
);
</script>

<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-8 sm:translate-y-0 sm:-translate-x-8"
        leave-active-class="transition duration-300 ease-in"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="visible"
            class="fixed z-50 bottom-5 left-1/2 -translate-x-1/2 sm:left-auto sm:right-6 sm:translate-x-0 w-[92%] sm:w-auto sm:max-w-sm"
        >
            <div
                class="flex items-center gap-3 rounded-2xl px-5 py-4 shadow-2xl ring-1 backdrop-blur"
                :class="type === 'success'
                    ? 'bg-pitch-600/95 ring-pitch-300/40 text-white'
                    : 'bg-red-600/95 ring-red-300/40 text-white'"
            >
                <FaIcon
                    :icon="['fas', type === 'success' ? 'circle-check' : 'circle-xmark']"
                    class="text-2xl"
                    :class="type === 'success' ? 'text-gold-300' : 'text-white'"
                />
                <p class="font-semibold leading-snug">{{ message }}</p>
            </div>
        </div>
    </Transition>
</template>
