<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Acceso anfitrión" />

    <div class="min-h-[100svh] flex items-center justify-center px-5 bg-gradient-to-b from-pitch-700 via-pitch-800 to-pitch-900">
        <div class="absolute inset-0 bg-pitch-stripes opacity-40"></div>

        <div
            v-motion
            :initial="{ opacity: 0, y: 30 }"
            :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
            class="relative w-full max-w-sm"
        >
            <div class="text-center mb-7">
                <span class="text-6xl drop-shadow-gold inline-block animate-float">⚽</span>
                <h1 class="mt-3 font-display text-4xl text-gold-shine tracking-wide">Panel del anfitrión</h1>
                <p class="text-pitch-100/70 text-sm mt-1">Ingresa para ver las confirmaciones</p>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded-3xl bg-pitch-700/60 ring-1 ring-white/10 p-7 backdrop-blur shadow-2xl space-y-5"
            >
                <div>
                    <label for="email" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">Correo</label>
                    <div class="relative">
                        <FaIcon :icon="['fas', 'envelope']" class="absolute left-4 top-1/2 -translate-y-1/2 text-pitch-100/40" />
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autofocus
                            class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 pl-11 pr-4 py-3.5 text-white placeholder-pitch-100/40 outline-none transition"
                            :class="{ 'ring-red-400 ring-2': form.errors.email }"
                        />
                    </div>
                    <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-300">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-pitch-100/80 mb-1.5">Contraseña</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-2xl bg-pitch-800/70 ring-1 ring-white/10 focus:ring-2 focus:ring-gold-400 px-4 py-3.5 text-white outline-none transition"
                        :class="{ 'ring-red-400 ring-2': form.errors.password }"
                    />
                    <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-300">{{ form.errors.password }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-pitch-100/70 select-none cursor-pointer">
                    <input v-model="form.remember" type="checkbox" class="rounded accent-gold-500" />
                    Mantener sesión iniciada
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex items-center justify-center gap-2 rounded-full bg-gold-500 hover:bg-gold-400 text-pitch-900 font-extrabold py-3.5 shadow-xl shadow-gold-500/25 transition-all active:scale-95 disabled:opacity-70"
                >
                    <FaIcon :icon="['fas', form.processing ? 'spinner' : 'right-from-bracket']" :class="{ 'animate-spin': form.processing }" />
                    {{ form.processing ? 'Entrando...' : 'Entrar' }}
                </button>
            </form>

            <p class="text-center mt-5">
                <a href="/" class="text-sm text-pitch-100/60 hover:text-gold-300 transition">← Volver a la invitación</a>
            </p>
        </div>
    </div>
</template>
