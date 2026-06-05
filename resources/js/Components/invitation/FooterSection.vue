<script setup>
import Reveal from '../ui/Reveal.vue';

defineProps({
    event: { type: Object, required: true },
});
</script>

<template>
    <footer class="relative py-14 px-5 bg-pitch-900 text-center overflow-hidden">
        <div class="absolute inset-0 bg-pitch-stripes opacity-30"></div>
        <Reveal>
            <div class="relative z-10">
                <span class="text-4xl animate-float inline-block">🏆</span>
                <p class="mt-4 font-display text-3xl sm:text-4xl text-gold-shine tracking-wide">{{ event.hostName }}</p>
                <p class="mt-2 text-pitch-100/70 max-w-md mx-auto">{{ event.footerNote }}</p>

                <div class="mt-7">
                    <p class="text-pitch-100/60 text-sm">Con cariño,</p>
                    <p class="mt-1 font-display text-2xl sm:text-3xl text-white tracking-wide">{{ event.hosts }}</p>
                    <FaIcon :icon="['fas', 'heart']" class="mt-2 text-gold-400" />
                </div>

                <div class="mt-6 flex items-center justify-center gap-2 text-pitch-100/40 text-sm">
                    <FaIcon :icon="['fas', 'futbol']" />
                    <span>{{ event.dateLabel }}</span>
                </div>
            </div>
        </Reveal>
    </footer>
</template>
