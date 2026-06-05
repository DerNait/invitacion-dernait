<script setup>
import { computed } from 'vue';

/**
 * Envuelve contenido que aparece con animación al entrar en el viewport.
 * Usa la directiva v-motion de @vueuse/motion (visibleOnce).
 */
const props = defineProps({
    delay: { type: Number, default: 0 },
    y: { type: Number, default: 40 },
    x: { type: Number, default: 0 },
    duration: { type: Number, default: 650 },
    scale: { type: Number, default: 1 },
});

const initial = computed(() => ({
    opacity: 0,
    y: props.y,
    x: props.x,
    scale: props.scale === 1 ? 0.98 : props.scale,
}));

const visibleOnce = computed(() => ({
    opacity: 1,
    y: 0,
    x: 0,
    scale: 1,
    transition: {
        duration: props.duration,
        delay: props.delay,
        type: 'spring',
        stiffness: 90,
        damping: 18,
    },
}));
</script>

<template>
    <div v-motion :initial="initial" :visible-once="visibleOnce">
        <slot />
    </div>
</template>
