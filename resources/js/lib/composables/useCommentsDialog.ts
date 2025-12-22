import { ComputedRef, inject, Ref } from 'vue';

export const useCommentsDialog = () => {
    const obj = inject<{
        activeThread: Ref<
            | {
                  parentId: string | null;
                  id: string;
              }[]
            | null
        >;
        setActiveThread: (val: { parentId: number | null; id: number }) => void;
        goBack: () => void;
        postId: ComputedRef<number>;
    }>('comments-dialog');

    if (!obj) throw new Error('useCommentsDialog must be used inside comments-dialog provider');

    return obj;
};
