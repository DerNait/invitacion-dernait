import { reactive } from 'vue';

// Estado compartido (singleton) del visor de imágenes a pantalla completa.
const state = reactive({
    isOpen: false,
    src: '',
    alt: '',
});

export function useLightbox() {
    const open = (src, alt = '') => {
        state.src = src;
        state.alt = alt;
        state.isOpen = true;
    };

    const close = () => {
        state.isOpen = false;
    };

    return { state, open, close };
}
