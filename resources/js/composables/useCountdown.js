import { ref, computed, onMounted, onUnmounted } from 'vue';

/**
 * Cuenta regresiva hacia una fecha objetivo (ISO 8601).
 * Devuelve días/horas/minutos/segundos reactivos y si ya pasó el evento.
 */
export function useCountdown(targetIso) {
    const now = ref(Date.now());
    const target = new Date(targetIso).getTime();
    let timer = null;

    onMounted(() => {
        now.value = Date.now();
        timer = setInterval(() => (now.value = Date.now()), 1000);
    });

    onUnmounted(() => timer && clearInterval(timer));

    const diff = computed(() => Math.max(0, target - now.value));
    const isPast = computed(() => target - now.value <= 0);

    const pad = (n) => String(n).padStart(2, '0');

    const days = computed(() => Math.floor(diff.value / 86400000));
    const hours = computed(() => pad(Math.floor((diff.value % 86400000) / 3600000)));
    const minutes = computed(() => pad(Math.floor((diff.value % 3600000) / 60000)));
    const seconds = computed(() => pad(Math.floor((diff.value % 60000) / 1000)));

    return { days, hours, minutes, seconds, isPast };
}
