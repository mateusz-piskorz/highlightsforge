import { fetchWithXsrf } from '@/lib/utils';
import { useQuery } from '@tanstack/vue-query';
import { reactive, toRefs, watch } from 'vue';

type User = { user_name?: string };

export function useUser() {
    // reactive user object
    const user = reactive<User>({ user_name: '' });

    const query = useQuery({
        queryKey: ['useUser'],
        queryFn: async (): Promise<User | undefined> => {
            const res = await fetchWithXsrf('/api/user');
            return res.ok ? res.json() : undefined;
        },
        staleTime: 1000 * 60 * 5,
    });

    // sync the reactive object with async query.data
    // NOTE: watch(query.data, ...) is important — it watches the Ref's value.
    watch(
        query.data,
        (d) => {
            Object.assign(user, d ?? {});
        },
        { immediate: true },
    );

    // return toRefs(user) so callers can destructure and keep refs
    return {
        ...toRefs(user), // now const { user_name } = useUser() gives a Ref<string>
        isLoading: query.isLoading,
        refresh: query.refetch,
    };
}
