<script setup>
import Reveal from '../ui/Reveal.vue';
import { useLightbox } from '../../composables/useLightbox';

defineProps({
    event: { type: Object, required: true },
});

const lightbox = useLightbox();
</script>

<template>
    <section id="dresscode" class="relative py-20 px-5 sm:px-8 bg-pitch-800">
        <div class="mx-auto max-w-4xl">
            <Reveal>
                <div class="text-center mb-10">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">EL UNIFORME</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">{{ event.dressCode.title }}</h2>
                </div>
            </Reveal>

            <div class="grid md:grid-cols-2 gap-7 items-center">
                <Reveal :x="-40">
                    <button
                        type="button"
                        @click="lightbox.open(event.dressCode.image, event.dressCode.title)"
                        class="group relative block w-full"
                    >
                        <div class="absolute -inset-3 rounded-3xl bg-gold-500/20 blur-2xl"></div>
                        <img
                            :src="event.dressCode.image"
                            :alt="event.dressCode.title"
                            class="relative w-full rounded-3xl ring-1 ring-white/10 object-cover shadow-2xl cursor-zoom-in transition-transform duration-300 group-hover:scale-[1.01]"
                            loading="lazy"
                        />
                        <span class="absolute bottom-3 right-3 grid place-items-center w-10 h-10 rounded-full bg-pitch-900/70 ring-1 ring-white/20 text-white backdrop-blur transition group-hover:bg-gold-500 group-hover:text-pitch-900">
                            <FaIcon :icon="['fas', 'expand']" />
                        </span>
                    </button>
                </Reveal>

                <Reveal :delay="150" :x="40">
                    <div class="rounded-3xl bg-pitch-700/50 ring-1 ring-white/10 p-7 backdrop-blur">
                        <div class="flex items-center gap-3 mb-4">
                            <FaIcon :icon="['fas', 'shirt']" class="text-gold-400 text-3xl" />
                            <FaIcon :icon="['fas', 'futbol']" class="text-white/70 text-2xl animate-spin-slow" />
                        </div>
                        <p class="text-lg sm:text-xl text-pitch-100/90 leading-relaxed">
                            {{ event.dressCode.description }}
                        </p>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>
</template>
