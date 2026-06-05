<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import HeroSection from '../Components/invitation/HeroSection.vue';
import CountdownSection from '../Components/invitation/CountdownSection.vue';
import EventDetailsSection from '../Components/invitation/EventDetailsSection.vue';
import MatchSection from '../Components/invitation/MatchSection.vue';
import LocationMapSection from '../Components/invitation/LocationMapSection.vue';
import ParkingSection from '../Components/invitation/ParkingSection.vue';
import FoodSection from '../Components/invitation/FoodSection.vue';
import DressCodeSection from '../Components/invitation/DressCodeSection.vue';
import RsvpFormSection from '../Components/invitation/RsvpFormSection.vue';
import FooterSection from '../Components/invitation/FooterSection.vue';
import Toast from '../Components/ui/Toast.vue';
import Lightbox from '../Components/ui/Lightbox.vue';

defineProps({
    event: { type: Object, required: true },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success || '');
const flashError = computed(() => page.props.flash?.error || '');
</script>

<template>
    <Head :title="`${event.title} ${event.hostName}`" />

    <main class="bg-pitch-900 text-white selection:bg-gold-500 selection:text-pitch-900">
        <HeroSection :event="event" />
        <CountdownSection :event="event" />
        <EventDetailsSection :event="event" />
        <MatchSection :event="event" />
        <LocationMapSection :event="event" />
        <ParkingSection :event="event" />
        <FoodSection :event="event" />
        <DressCodeSection :event="event" />
        <RsvpFormSection :event="event" />
        <FooterSection :event="event" />
    </main>

    <Toast v-if="flashSuccess" :message="flashSuccess" type="success" />
    <Toast v-if="flashError" :message="flashError" type="error" />

    <Lightbox />
</template>
