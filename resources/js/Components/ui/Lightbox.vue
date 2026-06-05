<script setup>
import { ref, watch, onUnmounted } from 'vue';
import { useLightbox } from '../../composables/useLightbox';

const { state, close } = useLightbox();

const MIN = 1;
const MAX = 4;

const scale = ref(1);
const tx = ref(0);
const ty = ref(0);
const imgEl = ref(null);

// --- interacción ---
let dragging = false;
let panning = false;
let pinching = false;
let startX = 0;
let startY = 0;
let startDist = 0;
let startScale = 1;
let lastTap = 0;

const clamp = (v, min, max) => Math.min(max, Math.max(min, v));

const resetTransform = () => {
    scale.value = 1;
    tx.value = 0;
    ty.value = 0;
};

// Mantiene la imagen dentro de límites razonables al hacer pan.
const clampPan = () => {
    const el = imgEl.value;
    if (!el) return;
    const w = el.clientWidth * scale.value;
    const h = el.clientHeight * scale.value;
    const maxX = Math.max(0, (w - window.innerWidth) / 2 + 40);
    const maxY = Math.max(0, (h - window.innerHeight) / 2 + 40);
    tx.value = clamp(tx.value, -maxX, maxX);
    ty.value = clamp(ty.value, -maxY, maxY);
};

const zoomBy = (factor) => {
    scale.value = clamp(scale.value * factor, MIN, MAX);
    if (scale.value === 1) resetTransform();
    else clampPan();
};

const toggleZoom = () => {
    if (scale.value > 1) resetTransform();
    else {
        scale.value = 2.5;
        clampPan();
    }
};

// Rueda (desktop)
const onWheel = (e) => {
    e.preventDefault();
    zoomBy(e.deltaY < 0 ? 1.15 : 0.87);
};

// Mouse (desktop)
const onMouseDown = (e) => {
    if (scale.value <= 1) return;
    dragging = true;
    startX = e.clientX - tx.value;
    startY = e.clientY - ty.value;
};
const onMouseMove = (e) => {
    if (!dragging) return;
    tx.value = e.clientX - startX;
    ty.value = e.clientY - startY;
    clampPan();
};
const onMouseUp = () => (dragging = false);

// Táctil (móvil): pellizco para zoom, un dedo para mover
const dist = (t) => Math.hypot(t[0].clientX - t[1].clientX, t[0].clientY - t[1].clientY);

const onTouchStart = (e) => {
    if (e.touches.length === 2) {
        pinching = true;
        startDist = dist(e.touches);
        startScale = scale.value;
    } else if (e.touches.length === 1) {
        const now = Date.now();
        if (now - lastTap < 300) toggleZoom();
        lastTap = now;
        if (scale.value > 1) {
            panning = true;
            startX = e.touches[0].clientX - tx.value;
            startY = e.touches[0].clientY - ty.value;
        }
    }
};
const onTouchMove = (e) => {
    if (pinching && e.touches.length === 2) {
        e.preventDefault();
        scale.value = clamp(startScale * (dist(e.touches) / startDist), MIN, MAX);
        clampPan();
    } else if (panning && e.touches.length === 1) {
        e.preventDefault();
        tx.value = e.touches[0].clientX - startX;
        ty.value = e.touches[0].clientY - startY;
        clampPan();
    }
};
const onTouchEnd = (e) => {
    if (e.touches.length === 0) {
        panning = false;
        pinching = false;
        if (scale.value <= 1) resetTransform();
    }
};

const onKey = (e) => {
    if (!state.isOpen) return;
    if (e.key === 'Escape') close();
    if (e.key === '+' || e.key === '=') zoomBy(1.2);
    if (e.key === '-') zoomBy(0.8);
};

// Bloquea el scroll del fondo y resetea el zoom cada vez que se abre/cierra.
watch(
    () => state.isOpen,
    (open) => {
        resetTransform();
        if (typeof document === 'undefined') return;
        document.body.style.overflow = open ? 'hidden' : '';
        if (open) {
            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
            window.addEventListener('keydown', onKey);
        } else {
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);
            window.removeEventListener('keydown', onKey);
        }
    }
);

onUnmounted(() => {
    document.body.style.overflow = '';
    window.removeEventListener('mousemove', onMouseMove);
    window.removeEventListener('mouseup', onMouseUp);
    window.removeEventListener('keydown', onKey);
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="opacity-0"
        >
            <div
                v-if="state.isOpen"
                class="fixed inset-0 z-[100] bg-pitch-900/95 backdrop-blur-sm flex items-center justify-center overscroll-contain select-none"
                @click.self="close"
                @wheel="onWheel"
            >
                <!-- Controles -->
                <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                    <button
                        @click="zoomBy(0.8)"
                        class="grid place-items-center w-11 h-11 rounded-full bg-pitch-700/80 hover:bg-pitch-600 ring-1 ring-white/15 text-white transition active:scale-90"
                        aria-label="Alejar"
                    >
                        <FaIcon :icon="['fas', 'magnifying-glass-minus']" />
                    </button>
                    <button
                        @click="zoomBy(1.25)"
                        class="grid place-items-center w-11 h-11 rounded-full bg-pitch-700/80 hover:bg-pitch-600 ring-1 ring-white/15 text-white transition active:scale-90"
                        aria-label="Acercar"
                    >
                        <FaIcon :icon="['fas', 'magnifying-glass-plus']" />
                    </button>
                    <button
                        @click="close"
                        class="grid place-items-center w-11 h-11 rounded-full bg-gold-500 hover:bg-gold-400 text-pitch-900 transition active:scale-90"
                        aria-label="Cerrar"
                    >
                        <FaIcon :icon="['fas', 'xmark']" class="text-lg" />
                    </button>
                </div>

                <!-- Imagen con zoom + pan -->
                <Transition
                    appear
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-90"
                >
                    <div
                        class="will-change-transform"
                        :style="{ transform: `translate(${tx}px, ${ty}px)` }"
                        @touchstart="onTouchStart"
                        @touchmove="onTouchMove"
                        @touchend="onTouchEnd"
                    >
                        <img
                            ref="imgEl"
                            :src="state.src"
                            :alt="state.alt"
                            draggable="false"
                            class="max-w-[92vw] max-h-[85vh] rounded-2xl shadow-2xl ring-1 ring-white/10 will-change-transform transition-transform duration-100"
                            :style="{ transform: `scale(${scale})`, cursor: scale > 1 ? 'grab' : 'zoom-in' }"
                            @dblclick="toggleZoom"
                            @mousedown="onMouseDown"
                        />
                    </div>
                </Transition>

                <!-- Pista de uso + descripción -->
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 text-center px-4">
                    <p v-if="state.alt" class="text-white/90 font-medium mb-1">{{ state.alt }}</p>
                    <p class="text-xs text-white/50">
                        <span class="hidden sm:inline">Doble clic o rueda para zoom · arrastra para mover · ESC para cerrar</span>
                        <span class="sm:hidden">Pellizca para zoom · arrastra para mover · toca fuera para cerrar</span>
                    </p>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
