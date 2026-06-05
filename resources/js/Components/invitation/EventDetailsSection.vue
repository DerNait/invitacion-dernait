<script setup>
import Reveal from '../ui/Reveal.vue';
import AddToCalendar from './AddToCalendar.vue';

defineProps({
    event: { type: Object, required: true },
});
</script>

<template>
    <section class="relative py-20 px-5 sm:px-8 bg-gradient-to-b from-pitch-900 to-pitch-800">
        <div class="mx-auto max-w-3xl">
            <Reveal>
                <div class="text-center mb-10">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">LA CONVOCATORIA</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">Detalles del partido</h2>
                    <p class="mt-4 text-pitch-100/80 leading-relaxed max-w-xl mx-auto">{{ event.description }}</p>
                </div>
            </Reveal>

            <div class="grid sm:grid-cols-2 gap-4">
                <Reveal :delay="100" :x="-30">
                    <div class="h-full rounded-3xl bg-pitch-700/60 ring-1 ring-white/10 p-6 flex items-center gap-4 backdrop-blur">
                        <div class="shrink-0 grid place-items-center w-14 h-14 rounded-2xl bg-gold-500/15 ring-1 ring-gold-500/30">
                            <FaIcon :icon="['fas', 'calendar-days']" class="text-gold-400 text-2xl" />
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-widest text-pitch-100/60">Fecha</p>
                            <p class="text-lg font-bold text-white">{{ event.dateLabel }}</p>
                        </div>
                    </div>
                </Reveal>

                <Reveal :delay="220" :x="30">
                    <div class="h-full rounded-3xl bg-pitch-700/60 ring-1 ring-white/10 p-6 flex items-center gap-4 backdrop-blur">
                        <div class="shrink-0 grid place-items-center w-14 h-14 rounded-2xl bg-gold-500/15 ring-1 ring-gold-500/30">
                            <FaIcon :icon="['fas', 'clock']" class="text-gold-400 text-2xl" />
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-widest text-pitch-100/60">Hora</p>
                            <p class="text-lg font-bold text-white">{{ event.timeLabel }}</p>
                        </div>
                    </div>
                </Reveal>
            </div>

            <Reveal :delay="320">
                <div class="mt-7 flex justify-center">
                    <AddToCalendar :event="event" />
                </div>
            </Reveal>
        </div>
    </section>
</template>
