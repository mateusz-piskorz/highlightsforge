import { inject, Ref } from 'vue';

export type SortOptions = 'featured' | 'new-posts' | 'latest-activity';

export const usePostFilters = () => {
    const obj = inject<{
        q: Ref<string | null>;
        setQ: (val: string | null) => void;
        sorting: Ref<SortOptions>;
        setSorting: (val: SortOptions) => void;
    }>('post-filters');

    if (!obj) throw new Error('usePostFilters must be used inside post-filters provider');

    return obj;
};
