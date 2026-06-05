<script setup>
import { useCountdown } from '../../composables/useCountdown';
import Reveal from '../ui/Reveal.vue';

const props = defineProps({
    event: { type: Object, required: true },
});

const { days, hours, minutes, seconds, isPast } = useCountdown(props.event.datetimeIso);

const units = [
    { label: 'Días', value: days },
    { label: 'Horas', value: hours },
    { label: 'Min', value: minutes },
    { label: 'Seg', value: seconds },
];
</script>

<template>
    <section class="relative py-16 px-5 bg-pitch-900">
        <div class="mx-auto max-w-3xl text-center">
            <Reveal>
                <p class="font-display text-gold-400 text-2xl tracking-widest mb-1">EL PITAZO INICIAL EN</p>
            </Reveal>

            <Reveal :delay="120">
                <div v-if="!isPast" class="mt-5 grid grid-cols-4 gap-2.5 sm:gap-4">
                    <div
                        v-for="u in units"
                        :key="u.label"
                        class="rounded-2xl bg-gradient-to-b from-pitch-700 to-pitch-800 ring-1 ring-white/10 py-4 sm:py-6 shadow-lg"
                    >
                        <div class="font-display text-4xl sm:text-6xl text-gold-shine tabular-nums">{{ u.value }}</div>
                        <div class="mt-1 text-[10px] sm:text-sm uppercase tracking-widest text-pitch-100/70">{{ u.label }}</div>
                    </div>
                </div>
                <div v-else class="mt-5 rounded-2xl bg-pitch-700 ring-1 ring-white/10 py-8">
                    <p class="font-display text-4xl text-gold-shine">¡HOY ES EL GRAN DÍA! ⚽</p>
                </div>
            </Reveal>
        </div>
    </section>
</template>
