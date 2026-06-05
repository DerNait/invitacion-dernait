<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import L from 'leaflet';
import Reveal from '../ui/Reveal.vue';
import { useLightbox } from '../../composables/useLightbox';

const props = defineProps({
    event: { type: Object, required: true },
});

const lightbox = useLightbox();

const mapEl = ref(null);
let map = null;

onMounted(() => {
    map = L.map(mapEl.value, {
        center: [props.event.mapLat, props.event.mapLng],
        zoom: props.event.mapZoom,
        scrollWheelZoom: false,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        maxZoom: 20,
    }).addTo(map);

    // Marcador personalizado con balón
    const icon = L.divIcon({
        className: 'custom-pin',
        html: `<div class="pin-wrap"><span class="pin-ball">⚽</span><span class="pin-pulse"></span></div>`,
        iconSize: [44, 44],
        iconAnchor: [22, 42],
        popupAnchor: [0, -40],
    });

    L.marker([props.event.mapLat, props.event.mapLng], { icon })
        .addTo(map)
        .bindPopup(`<strong>${props.event.venueName}</strong><br>${props.event.address}`);

    // Permitir zoom con la rueda solo tras click (mejor UX móvil)
    map.on('click', () => map.scrollWheelZoom.enable());
    map.on('mouseout', () => map.scrollWheelZoom.disable());

    setTimeout(() => map && map.invalidateSize(), 200);
});

onBeforeUnmount(() => {
    if (map) {
        map.remove();
        map = null;
    }
});
</script>

<template>
    <section id="ubicacion" class="relative py-20 px-5 sm:px-8">
        <div class="mx-auto max-w-3xl">
            <Reveal>
                <div class="text-center mb-8">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">EL ESTADIO</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">¿Dónde nos vemos?</h2>
                </div>
            </Reveal>

            <Reveal :delay="80">
                <button
                    type="button"
                    @click="lightbox.open(event.venueImage, event.venueName)"
                    class="group relative mb-6 block w-full text-left"
                >
                    <div class="absolute -inset-2 rounded-3xl bg-gold-500/15 blur-2xl"></div>
                    <img
                        :src="event.venueImage"
                        :alt="event.venueName"
                        class="relative w-full rounded-3xl ring-1 ring-white/10 object-cover shadow-2xl cursor-zoom-in transition-transform duration-300 group-hover:scale-[1.01]"
                        loading="lazy"
                    />
                    <span class="absolute bottom-3 right-3 grid place-items-center w-10 h-10 rounded-full bg-pitch-900/70 ring-1 ring-white/20 text-white backdrop-blur transition group-hover:bg-gold-500 group-hover:text-pitch-900">
                        <FaIcon :icon="['fas', 'expand']" />
                    </span>
                </button>
            </Reveal>

            <Reveal :delay="100">
                <div class="rounded-3xl bg-pitch-800/70 ring-1 ring-white/10 p-5 sm:p-7 backdrop-blur shadow-2xl">
                    <div class="flex items-start gap-3 mb-5">
                        <FaIcon :icon="['fas', 'location-dot']" class="text-gold-400 text-2xl mt-1" />
                        <div>
                            <p class="text-lg font-bold text-white">{{ event.venueName }}</p>
                            <p class="text-pitch-100/80">{{ event.address }}</p>
                        </div>
                    </div>

                    <div
                        ref="mapEl"
                        class="h-64 sm:h-80 w-full rounded-2xl overflow-hidden ring-1 ring-white/10 z-0"
                    ></div>

                    <a
                        :href="event.mapsUrl"
                        target="_blank"
                        rel="noopener"
                        class="group mt-5 flex items-center justify-center gap-2 w-full rounded-full bg-gold-500 hover:bg-gold-400 text-pitch-900 font-bold py-3.5 transition-all active:scale-95 shadow-lg shadow-gold-500/20"
                    >
                        <FaIcon :icon="['fas', 'route']" class="group-hover:translate-x-0.5 transition-transform" />
                        Cómo llegar
                    </a>
                </div>
            </Reveal>
        </div>
    </section>
</template>

<style>
.custom-pin .pin-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
}
.custom-pin .pin-ball {
    font-size: 30px;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.5));
    animation: float 2.5s ease-in-out infinite;
    z-index: 2;
}
.custom-pin .pin-pulse {
    position: absolute;
    bottom: 2px;
    width: 16px;
    height: 16px;
    border-radius: 9999px;
    background: rgba(245, 181, 15, 0.6);
    animation: pulseRing 2s ease-out infinite;
}
</style>
