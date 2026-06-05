<script setup>
import Reveal from '../ui/Reveal.vue';

defineProps({
    event: { type: Object, required: true },
});
</script>

<template>
    <section id="partido" class="relative py-20 px-5 sm:px-8 bg-pitch-800 overflow-hidden">
        <div class="absolute inset-0 bg-pitch-stripes opacity-30"></div>

        <div class="relative mx-auto max-w-3xl">
            <Reveal>
                <div class="text-center mb-9">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">EN VIVO DESDE LA FIESTA</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">{{ event.match.title }}</h2>
                </div>
            </Reveal>

            <Reveal :delay="120">
                <div class="rounded-3xl bg-gradient-to-b from-pitch-700 to-pitch-900 ring-1 ring-gold-500/25 p-7 sm:p-9 shadow-2xl">
                    <!-- Marcador / enfrentamiento -->
                    <div class="flex items-center justify-center gap-4 sm:gap-8">
                        <div class="flex-1 text-center">
                            <div class="text-5xl sm:text-7xl mb-2 animate-float">{{ event.match.homeFlag }}</div>
                            <p class="font-display text-2xl sm:text-3xl text-white tracking-wide leading-none">{{ event.match.home }}</p>
                        </div>

                        <div class="shrink-0 flex flex-col items-center">
                            <FaIcon :icon="['fas', 'futbol']" class="text-white/40 text-xl mb-1 animate-spin-slow" />
                            <span class="font-display text-3xl sm:text-4xl text-gold-shine">VS</span>
                        </div>

                        <div class="flex-1 text-center">
                            <div class="text-5xl sm:text-7xl mb-2 animate-float" style="animation-delay: .5s">{{ event.match.awayFlag }}</div>
                            <p class="font-display text-2xl sm:text-3xl text-white tracking-wide leading-none">{{ event.match.away }}</p>
                        </div>
                    </div>

                    <!-- Aviso tranquilizador -->
                    <div class="mt-8 flex items-start gap-3 rounded-2xl bg-gold-500/10 ring-1 ring-gold-500/25 p-5">
                        <FaIcon :icon="['fas', 'tv']" class="text-gold-400 text-2xl mt-0.5 shrink-0" />
                        <p class="text-pitch-100/90 leading-relaxed">{{ event.match.note }}</p>
                    </div>
                </div>
            </Reveal>
        </div>
    </section>
</template>
