<script setup>
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    event: { type: Object, required: true },
    rsvps: { type: Array, required: true },
    trashed: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
});

const page = usePage();
const filter = ref('all'); // all | attending | declined
const showHidden = ref(false);

// Muestra/oculta toda la parte de acompañantes (solo front). Pon en true para reactivar.
const showGuests = false;

const hide = (r) => {
    if (confirm(`¿Ocultar la confirmación de ${r.name}? No se borra de la base, solo deja de aparecer.`)) {
        router.delete(`/admin/rsvps/${r.id}`, { preserveScroll: true });
    }
};

const restore = (r) => {
    router.post(`/admin/rsvps/${r.id}/restore`, {}, { preserveScroll: true });
};

const filtered = computed(() => {
    if (filter.value === 'attending') return props.rsvps.filter((r) => r.attending);
    if (filter.value === 'declined') return props.rsvps.filter((r) => !r.attending);
    return props.rsvps;
});

const statCards = computed(() => {
    const cards = [
        { label: 'Respuestas', value: props.stats.responses, icon: 'chart-simple' },
        { label: 'Asisten', value: props.stats.attending, icon: 'circle-check' },
        { label: 'No asisten', value: props.stats.declined, icon: 'circle-xmark' },
    ];
    if (showGuests) {
        cards.push({ label: 'Total personas', value: props.stats.total_people, icon: 'people-group' });
    }
    return cards;
});

const logout = () => router.post('/logout');
</script>

<template>
    <Head title="Confirmaciones" />

    <div class="min-h-[100svh] bg-pitch-900 text-white">
        <!-- Header -->
        <header class="sticky top-0 z-20 bg-pitch-800/90 backdrop-blur ring-1 ring-white/10">
            <div class="mx-auto max-w-6xl px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="text-2xl">⚽</span>
                    <div class="min-w-0">
                        <h1 class="font-display text-2xl tracking-wide text-gold-shine leading-none">Confirmaciones</h1>
                        <p class="text-xs text-pitch-100/60 truncate">{{ event.title }} {{ event.hostName }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="hidden sm:block text-sm text-pitch-100/60">{{ page.props.auth.user?.name }}</span>
                    <button
                        @click="logout"
                        class="flex items-center gap-2 rounded-full bg-pitch-700 hover:bg-pitch-600 ring-1 ring-white/10 px-4 py-2 text-sm font-semibold transition active:scale-95"
                    >
                        <FaIcon :icon="['fas', 'right-from-bracket']" />
                        <span class="hidden sm:inline">Salir</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-5 py-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 sm:gap-4" :class="showGuests ? 'lg:grid-cols-4' : 'lg:grid-cols-3'">
                <div
                    v-for="(card, i) in statCards"
                    :key="card.label"
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{ opacity: 1, y: 0, transition: { delay: i * 80, duration: 500 } }"
                    class="rounded-3xl bg-gradient-to-b from-pitch-700 to-pitch-800 ring-1 ring-white/10 p-5 shadow-lg"
                >
                    <div class="flex items-center gap-2 text-pitch-100/60 text-sm mb-2">
                        <FaIcon :icon="['fas', card.icon]" class="text-gold-400" />
                        {{ card.label }}
                    </div>
                    <p class="font-display text-4xl sm:text-5xl text-gold-shine tabular-nums">{{ card.value }}</p>
                </div>
            </div>

            <!-- Filtros + acceso a ocultas -->
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="f in [
                            { key: 'all', label: 'Todas' },
                            { key: 'attending', label: 'Asisten' },
                            { key: 'declined', label: 'No asisten' },
                        ]"
                        :key="f.key"
                        @click="filter = f.key"
                        :class="filter === f.key
                            ? 'bg-gold-500 text-pitch-900'
                            : 'bg-pitch-700 text-pitch-100/80 hover:bg-pitch-600'"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition active:scale-95"
                    >
                        {{ f.label }}
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a
                        href="/admin/export"
                        class="inline-flex items-center gap-2 rounded-full bg-gold-500 hover:bg-gold-400 text-pitch-900 px-4 py-2 text-sm font-bold transition active:scale-95"
                        title="Descargar lista de asistentes en Word (.docx)"
                    >
                        <FaIcon :icon="['fas', 'file-word']" />
                        Exportar a Word
                    </a>

                    <button
                        v-if="trashed.length"
                        @click="showHidden = !showHidden"
                        :class="showHidden ? 'bg-pitch-600 text-white' : 'bg-pitch-700 text-pitch-100/80 hover:bg-pitch-600'"
                        class="inline-flex items-center gap-2 rounded-full ring-1 ring-white/10 px-4 py-2 text-sm font-semibold transition active:scale-95"
                    >
                        <FaIcon :icon="['fas', showHidden ? 'eye-slash' : 'eye']" />
                        {{ showHidden ? 'Ocultar lista' : `Ver ocultas (${trashed.length})` }}
                    </button>
                </div>
            </div>

            <!-- Confirmaciones ocultas -->
            <div v-if="showHidden && trashed.length" class="mt-5 rounded-3xl bg-pitch-800/50 ring-1 ring-white/10 p-5">
                <p class="text-sm text-pitch-100/60 mb-3 flex items-center gap-2">
                    <FaIcon :icon="['fas', 'eye-slash']" class="text-gold-400" />
                    Estas confirmaciones están ocultas (siguen guardadas en la base de datos).
                </p>
                <div class="space-y-2">
                    <div
                        v-for="r in trashed"
                        :key="r.id"
                        class="flex items-center justify-between gap-3 rounded-2xl bg-pitch-900/50 ring-1 ring-white/5 px-4 py-3"
                    >
                        <div class="min-w-0">
                            <p class="font-semibold truncate">
                                {{ r.name }}
                                <span
                                    :class="r.attending ? 'text-pitch-300' : 'text-red-300'"
                                    class="ml-2 text-xs font-bold"
                                >{{ r.attending ? 'Sí asiste' : 'No asiste' }}</span>
                            </p>
                            <p class="text-sm text-pitch-100/50 truncate">{{ r.email }}</p>
                        </div>
                        <button
                            @click="restore(r)"
                            class="shrink-0 inline-flex items-center gap-2 rounded-full bg-gold-500/90 hover:bg-gold-400 text-pitch-900 px-3.5 py-1.5 text-xs font-bold transition active:scale-95"
                        >
                            <FaIcon :icon="['fas', 'rotate-left']" /> Restaurar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lista vacía -->
            <div v-if="filtered.length === 0" class="mt-10 text-center text-pitch-100/50 py-16">
                <span class="text-5xl block mb-3">🥅</span>
                Aún no hay confirmaciones en esta categoría.
            </div>

            <!-- Tabla (desktop) -->
            <div v-else class="mt-5 hidden md:block overflow-hidden rounded-3xl ring-1 ring-white/10">
                <table class="w-full text-left">
                    <thead class="bg-pitch-800 text-pitch-100/60 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-4">Invitado</th>
                            <th class="px-5 py-4">Correo</th>
                            <th class="px-5 py-4 text-center">Asiste</th>
                            <th v-if="showGuests" class="px-5 py-4 text-center">Personas</th>
                            <th class="px-5 py-4">Mensaje</th>
                            <th class="px-5 py-4">Fecha</th>
                            <th class="px-5 py-4 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="r in filtered" :key="r.id" class="bg-pitch-800/40 hover:bg-pitch-700/40 transition">
                            <td class="px-5 py-4">
                                <span class="font-semibold">{{ r.name }}</span>
                                <div v-if="showGuests && r.guest_names && r.guest_names.length" class="mt-1.5 flex flex-wrap gap-1.5">
                                    <span
                                        v-for="(g, i) in r.guest_names"
                                        :key="i"
                                        class="inline-flex items-center gap-1 rounded-full bg-pitch-700/70 ring-1 ring-white/10 px-2.5 py-0.5 text-xs text-pitch-100/80"
                                    >
                                        <FaIcon :icon="['fas', 'user']" class="text-gold-400/70 text-[10px]" />{{ g }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-pitch-100/70">{{ r.email }}</td>
                            <td class="px-5 py-4 text-center">
                                <span
                                    :class="r.attending ? 'bg-pitch-500/20 text-pitch-200 ring-pitch-400/40' : 'bg-red-500/20 text-red-200 ring-red-400/40'"
                                    class="inline-flex items-center gap-1.5 rounded-full ring-1 px-3 py-1 text-xs font-bold"
                                >
                                    <FaIcon :icon="['fas', r.attending ? 'check' : 'xmark']" />
                                    {{ r.attending ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td v-if="showGuests" class="px-5 py-4 text-center tabular-nums">{{ r.attending ? r.total_people : '—' }}</td>
                            <td class="px-5 py-4 text-pitch-100/60 max-w-xs truncate">{{ r.message || '—' }}</td>
                            <td class="px-5 py-4 text-pitch-100/50 text-sm whitespace-nowrap">{{ r.created_at_label }}</td>
                            <td class="px-5 py-4 text-center">
                                <button
                                    @click="hide(r)"
                                    class="grid place-items-center w-9 h-9 mx-auto rounded-full bg-pitch-700 hover:bg-red-500/80 ring-1 ring-white/10 hover:ring-red-400/50 text-pitch-100/70 hover:text-white transition active:scale-90"
                                    title="Ocultar (no se borra de la base)"
                                >
                                    <FaIcon :icon="['fas', 'eye-slash']" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tarjetas (móvil) -->
            <div v-if="filtered.length" class="mt-5 md:hidden space-y-3">
                <div
                    v-for="r in filtered"
                    :key="r.id"
                    class="rounded-2xl bg-pitch-800/60 ring-1 ring-white/10 p-4"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold truncate">{{ r.name }}</p>
                            <p class="text-sm text-pitch-100/60 truncate">{{ r.email }}</p>
                        </div>
                        <span
                            :class="r.attending ? 'bg-pitch-500/20 text-pitch-200 ring-pitch-400/40' : 'bg-red-500/20 text-red-200 ring-red-400/40'"
                            class="shrink-0 inline-flex items-center gap-1.5 rounded-full ring-1 px-3 py-1 text-xs font-bold"
                        >
                            <FaIcon :icon="['fas', r.attending ? 'check' : 'xmark']" />
                            {{ r.attending ? 'Sí' : 'No' }}
                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-pitch-100/60">
                        <span v-if="r.attending">
                            <template v-if="showGuests"><FaIcon :icon="['fas', 'users']" class="mr-1.5 text-gold-400" />{{ r.total_people }} persona(s)</template>
                            <template v-else><FaIcon :icon="['fas', 'check']" class="mr-1.5 text-gold-400" />Asistirá</template>
                        </span>
                        <span v-else class="text-pitch-100/40">No asistirá</span>
                        <span>{{ r.created_at_label }}</span>
                    </div>
                    <div v-if="showGuests && r.guest_names && r.guest_names.length" class="mt-2 flex flex-wrap gap-1.5">
                        <span
                            v-for="(g, i) in r.guest_names"
                            :key="i"
                            class="inline-flex items-center gap-1 rounded-full bg-pitch-700/70 ring-1 ring-white/10 px-2.5 py-0.5 text-xs text-pitch-100/80"
                        >
                            <FaIcon :icon="['fas', 'user']" class="text-gold-400/70 text-[10px]" />{{ g }}
                        </span>
                    </div>
                    <p v-if="r.message" class="mt-2 text-sm text-pitch-100/70 border-t border-white/5 pt-2">
                        <FaIcon :icon="['fas', 'quote-left']" class="text-gold-400/60 mr-1.5 text-xs" />{{ r.message }}
                    </p>
                    <div class="mt-3 pt-3 border-t border-white/5 flex justify-end">
                        <button
                            @click="hide(r)"
                            class="inline-flex items-center gap-2 rounded-full bg-pitch-700 hover:bg-red-500/80 ring-1 ring-white/10 px-3.5 py-1.5 text-xs font-semibold text-pitch-100/80 hover:text-white transition active:scale-95"
                        >
                            <FaIcon :icon="['fas', 'eye-slash']" /> Ocultar
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
