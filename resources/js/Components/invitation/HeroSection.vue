<script setup>
defineProps({
    event: { type: Object, required: true },
});

const scrollToRsvp = () => {
    document.getElementById('confirmar')?.scrollIntoView({ behavior: 'smooth' });
};
</script>

<template>
    <section class="relative min-h-[100svh] flex flex-col items-center justify-center overflow-hidden px-5 text-center">
        <!-- Fondo cancha -->
        <div class="absolute inset-0 bg-gradient-to-b from-pitch-700 via-pitch-800 to-pitch-900"></div>
        <div class="absolute inset-0 bg-pitch-stripes opacity-60"></div>

        <!-- Círculo central de cancha -->
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[120vw] sm:w-[600px] aspect-square rounded-full border-2 border-white/10"></div>
        <div class="absolute left-1/2 top-0 -translate-x-1/2 h-full w-px bg-white/10"></div>

        <!-- Balones flotando -->
        <span class="absolute top-[12%] left-[10%] text-4xl sm:text-5xl opacity-80 animate-bounce-ball" style="animation-delay: 0s">⚽</span>
        <span class="absolute top-[20%] right-[12%] text-3xl sm:text-4xl opacity-70 animate-float" style="animation-delay: .6s">🏆</span>
        <span class="absolute bottom-[18%] left-[14%] text-3xl opacity-70 animate-float" style="animation-delay: 1.1s">🥅</span>
        <span class="absolute bottom-[24%] right-[10%] text-4xl opacity-80 animate-bounce-ball" style="animation-delay: .3s">⚽</span>

        <!-- Contenido -->
        <div class="relative z-10 flex flex-col items-center">
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.6, rotate: -15 }"
                :enter="{ opacity: 1, scale: 1, rotate: 0, transition: { duration: 800, type: 'spring' } }"
                class="mb-5"
            >
                <div class="relative">
                    <div class="absolute inset-0 rounded-full bg-gold-500/30 blur-2xl"></div>
                    <span class="relative text-7xl sm:text-8xl drop-shadow-gold animate-float inline-block">⚽</span>
                </div>
            </div>

            <p
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{ opacity: 1, y: 0, transition: { delay: 300, duration: 600 } }"
                class="font-display text-2xl sm:text-3xl tracking-[0.3em] text-gold-400 mb-2"
            >
                {{ event.tagline }}
            </p>

            <h1
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { delay: 450, duration: 700 } }"
                class="font-display text-6xl sm:text-8xl leading-none tracking-wide text-gold-shine drop-shadow-gold"
            >
                {{ event.hostName }}
            </h1>

            <p
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{ opacity: 1, y: 0, transition: { delay: 650, duration: 600 } }"
                class="mt-3 text-lg sm:text-2xl text-white/90 font-medium"
            >
                {{ event.title }} <span class="block sm:inline text-pitch-100/80">{{ event.subtitle }}</span>
            </p>

            <button
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{ opacity: 1, y: 0, transition: { delay: 850, duration: 600 } }"
                @click="scrollToRsvp"
                class="group mt-9 inline-flex items-center gap-2.5 rounded-full bg-gold-500 hover:bg-gold-400 px-8 py-4 text-pitch-900 font-extrabold text-lg shadow-xl shadow-gold-500/30 transition-all active:scale-95 hover:scale-105"
            >
                <FaIcon :icon="['fas', 'futbol']" class="group-hover:rotate-180 transition-transform duration-500" />
                Confirmar asistencia
            </button>
        </div>

        <!-- Indicador de scroll -->
        <div class="absolute bottom-7 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1.5 text-white/60 animate-float">
            <span class="text-xs tracking-widest uppercase">Desliza</span>
            <FaIcon :icon="['fas', 'arrow-down']" />
        </div>
    </section>
</template>
