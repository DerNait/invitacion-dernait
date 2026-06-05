<script setup>
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core';

defineProps({
    event: { type: Object, required: true },
});

const open = ref(false);
const root = ref(null);

onClickOutside(root, () => (open.value = false));
</script>

<template>
    <div ref="root" class="relative inline-block text-left">
        <button
            type="button"
            @click="open = !open"
            class="group inline-flex items-center gap-2.5 rounded-full bg-pitch-700/70 hover:bg-pitch-600 ring-1 ring-white/15 hover:ring-gold-400/40 px-6 py-3 text-white font-semibold transition-all active:scale-95"
        >
            <FaIcon :icon="['fas', 'calendar-plus']" class="text-gold-400 group-hover:scale-110 transition-transform" />
            Agregar al calendario
        </button>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2 scale-95"
            leave-active-class="transition duration-150 ease-in"
            leave-to-class="opacity-0 -translate-y-2 scale-95"
        >
            <div
                v-if="open"
                class="absolute left-1/2 -translate-x-1/2 mt-2 w-60 z-50 rounded-2xl bg-pitch-700 ring-1 ring-white/15 shadow-2xl overflow-hidden"
            >
                <a
                    :href="event.googleCalendarUrl"
                    target="_blank"
                    rel="noopener"
                    @click="open = false"
                    class="flex items-center gap-3 px-4 py-3.5 text-left text-white hover:bg-pitch-600 transition"
                >
                    <FaIcon :icon="['fab', 'google']" class="text-lg w-5 text-gold-300" />
                    Google Calendar
                </a>
                <div class="h-px bg-white/10"></div>
                <a
                    :href="event.icsUrl"
                    @click="open = false"
                    class="flex items-center gap-3 px-4 py-3.5 text-left text-white hover:bg-pitch-600 transition"
                >
                    <FaIcon :icon="['fab', 'apple']" class="text-lg w-5 text-gold-300" />
                    Apple / Outlook
                    <span class="ml-auto text-xs text-pitch-100/50">.ics</span>
                </a>
            </div>
        </Transition>
    </div>
</template>
