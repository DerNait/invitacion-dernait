import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { MotionPlugin } from '@vueuse/motion';

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faFutbol, faLocationDot, faCalendarDays, faClock, faShirt, faTrophy,
    faCheck, faXmark, faUser, faEnvelope, faUsers, faRoute, faSpinner,
    faPaperPlane, faCircleCheck, faCircleXmark, faArrowDown, faRightFromBracket,
    faChartSimple, faPeopleGroup, faQuoteLeft, faHeart, faMapLocationDot,
    faUtensils, faBowlFood, faMoon, faTruckFast, faSquareParking, faTv,
    faMagnifyingGlassPlus, faMagnifyingGlassMinus, faExpand,
    faEye, faEyeSlash, faRotateLeft, faTrashCan, faDownload, faCalendarPlus,
} from '@fortawesome/free-solid-svg-icons';
import { faWhatsapp, faGoogle, faApple } from '@fortawesome/free-brands-svg-icons';

library.add(
    faFutbol, faLocationDot, faCalendarDays, faClock, faShirt, faTrophy,
    faCheck, faXmark, faUser, faEnvelope, faUsers, faRoute, faSpinner,
    faPaperPlane, faCircleCheck, faCircleXmark, faArrowDown, faRightFromBracket,
    faChartSimple, faPeopleGroup, faQuoteLeft, faHeart, faMapLocationDot,
    faUtensils, faBowlFood, faMoon, faTruckFast, faSquareParking, faTv,
    faMagnifyingGlassPlus, faMagnifyingGlassMinus, faExpand,
    faEye, faEyeSlash, faRotateLeft, faTrashCan, faDownload, faCalendarPlus,
    faWhatsapp, faGoogle, faApple,
);

const appName = import.meta.env.VITE_APP_NAME || 'Invitación';

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(MotionPlugin)
            .component('FaIcon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#f5b50f',
        showSpinner: true,
    },
});
