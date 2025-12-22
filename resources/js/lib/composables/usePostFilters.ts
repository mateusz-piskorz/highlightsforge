import { inject, Ref } from 'vue';

export const usePostFilters = () => {
    const obj = inject<{
        q: Ref<string | null>;
        setQ: (val: string | null) => void;
    }>('post-filters');

    if (!obj) throw new Error('usePostFilters must be used inside post-filters provider');

    return obj;
};
