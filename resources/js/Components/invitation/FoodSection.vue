<script setup>
import Reveal from '../ui/Reveal.vue';

defineProps({
    event: { type: Object, required: true },
});
</script>

<template>
    <section id="comida" class="relative py-20 px-5 sm:px-8 bg-gradient-to-b from-pitch-900 to-pitch-800">
        <div class="mx-auto max-w-4xl">
            <Reveal>
                <div class="text-center mb-10">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">EL TERCER TIEMPO</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">{{ event.food.title }}</h2>
                </div>
            </Reveal>

            <div class="grid md:grid-cols-2 gap-5">
                <!-- Almuerzo por nuestra cuenta -->
                <Reveal :x="-30">
                    <div class="h-full rounded-3xl bg-gradient-to-b from-gold-500/15 to-pitch-700/40 ring-1 ring-gold-500/25 p-7 backdrop-blur text-center sm:text-left">
                        <div class="inline-grid place-items-center w-14 h-14 rounded-2xl bg-gold-500/20 ring-1 ring-gold-500/40 mb-4">
                            <FaIcon :icon="['fas', 'utensils']" class="text-gold-400 text-2xl" />
                        </div>
                        <h3 class="font-display text-2xl text-white tracking-wide mb-2">El almuerzo va por nuestra cuenta</h3>
                        <p class="text-pitch-100/90 leading-relaxed">{{ event.food.description }}</p>
                    </div>
                </Reveal>

                <!-- Quédate al partido -->
                <Reveal :delay="150" :x="30">
                    <div class="h-full rounded-3xl bg-pitch-700/50 ring-1 ring-white/10 p-7 backdrop-blur text-center sm:text-left">
                        <div class="inline-flex items-center gap-2 mb-4">
                            <div class="grid place-items-center w-14 h-14 rounded-2xl bg-pitch-600/40 ring-1 ring-white/15">
                                <FaIcon :icon="['fas', 'moon']" class="text-gold-300 text-2xl" />
                            </div>
                            <FaIcon :icon="['fas', 'futbol']" class="text-white/50 text-xl animate-spin-slow" />
                        </div>
                        <h3 class="font-display text-2xl text-white tracking-wide mb-2">¿Te quedas al partido? 🌙⚽</h3>
                        <p class="text-pitch-100/90 leading-relaxed">{{ event.food.note }}</p>
                        <div class="mt-4 flex items-center gap-2 text-sm text-gold-300/90 justify-center sm:justify-start">
                            <FaIcon :icon="['fas', 'truck-fast']" />
                            <span>Pide a domicilio a la misma dirección</span>
                        </div>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>
</template>
