import { onMounted, onUnmounted, ref } from 'vue';

export function useMobile(breakpoint: number = 768) {
    const isMobile = ref(false);

    const checkMobile = () => {
        isMobile.value = window.innerWidth < breakpoint;
    };

    onMounted(() => {
        checkMobile();

        window.addEventListener('resize', checkMobile);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', checkMobile);
    });

    return isMobile;
}
