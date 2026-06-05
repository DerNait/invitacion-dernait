<script setup>
import { ref, watch, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Reveal from '../ui/Reveal.vue';

const props = defineProps({
    event: { type: Object, required: true },
});

const submitted = ref(false);
const lastAttending = ref(true);

const form = useForm({
    name: '',
    email: '',
    attending: true,
    guests_count: 0,
    guest_names: [],
    message: '',
});

// Muestra/oculta toda la parte de acompañantes (solo front). Pon en true para reactivar.
const showGuests = false;

// Límite visible en el desplegable (solo front). El backend sigue permitiendo
// hasta event.rsvp.maxGuests; sube este número para mostrar más opciones.
const visibleMaxGuests = 1;
const guestOptions = Array.from({ length: visibleMaxGuests + 1 }, (_, i) => i);

// Mantiene el arreglo de nombres con el mismo largo que el número de acompañantes.
watch(
    () => form.guests_count,
    (count) => {
        const names = form.guest_names.slice(0, count);
        while (names.length < count) names.push('');
        form.guest_names = names;
    }
);

const setAttending = (value) => {
    form.attending = value;
    if (!value) {
        form.guests_count = 0;
        form.guest_names = [];
    }
};

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            attending: data.attending ? 1 : 0,
        }))
        .post('/rsvp', {
            preserveScroll: true,
            onSuccess: () => {
                lastAttending.value = form.attending;
                submitted.value = true;
                // Lleva suavemente a la sección de confirmación para ver el mensaje de éxito.
                nextTick(() => {
                    document.getElementById('confirmar')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            },
        });
};

const resetForm = () => {
    submitted.value = false;
    form.reset();
    form.clearErrors();
};
</script>

<template>
    <section id="confirmar" class="relative py-20 px-5 sm:px-8 bg-gradient-to-b from-pitch-800 to-pitch-900">
        <div class="mx-auto max-w-xl">
            <Reveal>
                <div class="text-center mb-8">
                    <p class="font-display text-gold-400 text-2xl tracking-widest">TU LUGAR EN LA CANCHA</p>
                    <h2 class="font-display text-4xl sm:text-5xl text-white tracking-wide">Confirma tu asistencia</h2>
                    <p class="mt-3 text-sm text-pitch-100/70">{{ event.rsvp.deadlineLabel }}</p>
                </div>
            </Reveal>

            <Reveal :delay="120">
                <!-- Estado confirmado -->
                <Transition
                    mode="out-in"
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    leave-active-class="transition duration-200 ease-in"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="submitted"
                        key="done"
                        class="rounded-3xl bg-pitch-700/70 ring-1 ring-gold-500/30 p-8 text-center backdrop-blur shadow-2xl"
                    >
                        <div class="mx-auto grid place-items-center w-20 h-20 rounded-full bg-gold-500/15 ring-1 ring-gold-500/40 mb-5">
                            <FaIcon
                                :icon="['fas', lastAttending ? 'circle-check' : 'heart']"
                                class="text-gold-400 text-4xl"
                            />
                        </div>
                        <h3 class="font-display text-3xl text-white tracking-wide mb-2">
                            {{ lastAttending ? '¡Estás dentro! ⚽' : '¡Gracias por avisar!' }}
                        </h3>
                        <p class="text-pitch-100/80">
                            {{ lastAttending
                                ? 'Tu lugar quedó reservado. Te enviamos un correo con todos los detalles.'
                                : 'Lamentamos que no puedas asistir. ¡Te vamos a extrañar!' }}
                        </p>

                        <div class="mt-6 pt-5 border-t border-white/10">
                            <p class="text-pitch-100/60 text-sm">Con cariño,</p>
                            <p class="mt-1 font-display text-2xl text-gold-shine tracking-wide">{{ event.hosts }}</p>
                        </div>

                        <button
                            @click="resetForm"
                            class="mt-6 text-sm font-semibold text-gold-400 hover:text-gold-300 underline underline-offset-4"
                        >
                            Editar mi respuesta
                        </button>
                    </div>

                    <!-- Formulario -->
                    <form
                        v-else
                        key="form"
                        @submit.prevent="submit"
                        class="rounded-3xl bg-pitch-700/50 ring-1 ring-white/10 p-6 sm:p-8 backdrop-blur shadow-2xl space-y-5"
                    >
                        <!-- Asistencia -->
                        <div>
                            <label class="block text-sm font-semibold text-pitch-100/80 mb-2">¿Podrás asistir?</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    type="button"
                                    @click="setAttending(true)"
                                    :class="form.attending
                                        ? 'bg-gold-500 text-pitch-900 ring-gold-400 shadow-lg shadow-gold-500/20'
                                        : 'bg-pitch-800/60 text-white ring-white/10 hover:ring-white/30'"
                                    class="flex items-center justify-center gap-2 rounded-2xl ring-1 py-3.5 font-bold transition-all active:scale-95"
                                >
                                    <FaIcon :icon="['fas', 'check']" /> ¡Sí, voy!
                                </button>
                                <button
                                    type="button"
                                    @click="setAttending(false)"
                                    :class="!form.attending
                                        ? 'bg-red-500 text-white ring-red-400 shadow-lg shadow-red-500/20'
                                        : 'bg-pitch-800/60 text-white ring-white/10 hover:ring-white/30'"
                                    class="flex items-center justify-center gap-2 rounded-2xl ring-1 py-3.5 font-bold transition-all active:scale-95"
                                >
                                    <FaIcon :icon="['fas', 'xmark']" /> No podré
                                </button>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">
                                Nombre completo <span class="text-gold-400">*</span>
                            </label>
                            <div class="relative">
                                <FaIcon :icon="['fas', 'user']" class="absolute left-4 top-1/2 -translate-y-1/2 text-pitch-100/40" />
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Tu nombre"
                                    class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 pl-11 pr-4 py-3.5 text-white placeholder-pitch-100/40 outline-none transition"
                                    :class="{ 'ring-red-400 ring-2': form.errors.name }"
                                />
                            </div>
                            <p v-if="form.errors.name" class="mt-1.5 text-sm text-red-300">{{ form.errors.name }}</p>
                        </div>

                        <!-- Correo -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">
                                Correo electrónico <span class="text-gold-400">*</span>
                            </label>
                            <div class="relative">
                                <FaIcon :icon="['fas', 'envelope']" class="absolute left-4 top-1/2 -translate-y-1/2 text-pitch-100/40" />
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="tucorreo@email.com"
                                    class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 pl-11 pr-4 py-3.5 text-white placeholder-pitch-100/40 outline-none transition"
                                    :class="{ 'ring-red-400 ring-2': form.errors.email }"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-300">{{ form.errors.email }}</p>
                        </div>

                        <!-- Acompañantes (solo si asiste) -->
                        <Transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-y-2"
                            leave-active-class="transition duration-200 ease-in"
                            leave-to-class="opacity-0 -translate-y-2"
                        >
                            <div v-if="form.attending && showGuests">
                                <label for="guests" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">
                                    ¿Cuántos acompañantes traes?
                                </label>
                                <div class="relative">
                                    <FaIcon :icon="['fas', 'users']" class="absolute left-4 top-1/2 -translate-y-1/2 text-pitch-100/40 z-10" />
                                    <select
                                        id="guests"
                                        v-model.number="form.guests_count"
                                        class="w-full appearance-none rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 pl-11 pr-4 py-3.5 text-white outline-none transition"
                                    >
                                        <option v-for="n in guestOptions" :key="n" :value="n">
                                            {{ n === 0 ? 'Solo yo' : `${n} acompañante${n > 1 ? 's' : ''}` }}
                                        </option>
                                    </select>
                                </div>
                                <p v-if="form.errors.guests_count" class="mt-1.5 text-sm text-red-300">{{ form.errors.guests_count }}</p>
                            </div>
                        </Transition>

                        <!-- Nombres de los acompañantes -->
                        <Transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-y-2"
                            leave-active-class="transition duration-200 ease-in"
                            leave-to-class="opacity-0 -translate-y-2"
                        >
                            <div v-if="form.attending && showGuests && form.guests_count > 0" class="space-y-3">
                                <label class="block text-sm font-semibold text-pitch-100/80">
                                    ¿Quiénes te acompañan?
                                </label>
                                <div
                                    v-for="(name, i) in form.guest_names"
                                    :key="i"
                                    class="relative"
                                >
                                    <FaIcon :icon="['fas', 'user']" class="absolute left-4 top-1/2 -translate-y-1/2 text-pitch-100/40" />
                                    <input
                                        v-model="form.guest_names[i]"
                                        type="text"
                                        :placeholder="`Nombre del acompañante ${i + 1}`"
                                        class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 pl-11 pr-4 py-3.5 text-white placeholder-pitch-100/40 outline-none transition"
                                        :class="{ 'ring-red-400 ring-2': form.errors[`guest_names.${i}`] }"
                                    />
                                    <p v-if="form.errors[`guest_names.${i}`]" class="mt-1.5 text-sm text-red-300">
                                        {{ form.errors[`guest_names.${i}`] }}
                                    </p>
                                </div>
                            </div>
                        </Transition>

                        <!-- Mensaje opcional -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">
                                Mensaje (opcional)
                            </label>
                            <textarea
                                id="message"
                                v-model="form.message"
                                rows="3"
                                placeholder="¿Algo que quieras decir? 🎉"
                                class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 px-4 py-3.5 text-white placeholder-pitch-100/40 outline-none transition resize-none"
                            ></textarea>
                        </div>

                        <!-- Enviar -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="group w-full flex items-center justify-center gap-2.5 rounded-full bg-gold-500 hover:bg-gold-400 text-pitch-900 font-extrabold text-lg py-4 shadow-xl shadow-gold-500/25 transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            <FaIcon
                                :icon="['fas', form.processing ? 'spinner' : 'paper-plane']"
                                :class="{ 'animate-spin': form.processing }"
                            />
                            {{ form.processing ? 'Enviando...' : 'Enviar confirmación' }}
                        </button>
                    </form>
                </Transition>
            </Reveal>
        </div>
    </section>
</template>
