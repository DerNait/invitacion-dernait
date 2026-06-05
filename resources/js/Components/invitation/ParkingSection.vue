<script setup>
import Reveal from '../ui/Reveal.vue';
import { useLightbox } from '../../composables/useLightbox';

defineProps({
    event: { type: Object, required: true },
});

const lightbox = useLightbox();
</script>

<template>
    <section id="parqueo" class="relative py-20 px-5 sm:px-8 bg-pitch-900">
        <div class="mx-auto max-w-4xl">
            <Reveal>
                <div class="text-center mb-10">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">ZONA DE JUEGO</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">{{ event.parking.title }}</h2>
                </div>
            </Reveal>

            <div class="grid md:grid-cols-2 gap-7 items-center">
                <Reveal :x="-40">
                    <div class="rounded-3xl bg-pitch-700/50 ring-1 ring-white/10 p-7 backdrop-blur">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="grid place-items-center w-12 h-12 rounded-2xl bg-gold-500/15 ring-1 ring-gold-500/30">
                                <span class="font-display text-3xl text-gold-400 leading-none">P</span>
                            </div>
                            <FaIcon :icon="['fas', 'futbol']" class="text-white/60 text-xl" />
                        </div>
                        <p class="text-lg text-pitch-100/90 leading-relaxed mb-4">{{ event.parking.description }}</p>
                        <ul class="space-y-3">
                            <li
                                v-for="(opt, i) in event.parking.options"
                                :key="i"
                                class="flex items-start gap-3 text-pitch-100/90"
                            >
                                <FaIcon :icon="['fas', 'location-dot']" class="text-gold-400 mt-1 shrink-0" />
                                <span>{{ opt }}</span>
                            </li>
                        </ul>
                    </div>
                </Reveal>

                <Reveal :delay="150" :x="40">
                    <figure class="relative">
                        <button
                            type="button"
                            @click="lightbox.open(event.parking.image, event.parking.imageCaption)"
                            class="group relative block w-full"
                        >
                            <div class="absolute -inset-3 rounded-3xl bg-gold-500/15 blur-2xl"></div>
                            <img
                                :src="event.parking.image"
                                :alt="event.parking.imageCaption"
                                class="relative w-full rounded-3xl ring-1 ring-white/10 object-cover shadow-2xl cursor-zoom-in transition-transform duration-300 group-hover:scale-[1.01]"
                                loading="lazy"
                            />
                            <span class="absolute bottom-3 right-3 grid place-items-center w-10 h-10 rounded-full bg-pitch-900/70 ring-1 ring-white/20 text-white backdrop-blur transition group-hover:bg-gold-500 group-hover:text-pitch-900">
                                <FaIcon :icon="['fas', 'expand']" />
                            </span>
                        </button>
                        <figcaption class="relative mt-3 text-center text-sm text-pitch-100/60">
                            <FaIcon :icon="['fas', 'location-dot']" class="text-gold-400 mr-1.5" />{{ event.parking.imageCaption }}
                        </figcaption>
                    </figure>
                </Reveal>
            </div>
        </div>
    </section>
</template>
