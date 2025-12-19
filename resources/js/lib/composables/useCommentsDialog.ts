import { inject } from 'vue';

export const useCommentsDialog = () => {
    const obj = inject<{
        activeThread:
            | {
                  parentId: string | null;
                  id: string;
              }[]
            | null;
        setActiveThread: (val: { parentId: number | null; id: number }) => void;
        goBack: () => void;
        clipId: number;
    }>('comments-dialog');

    if (!obj) {
        throw new Error(
            'useCommentsDialog  must be used inside comments-dialog provider',
        );
    }

    return obj;
};
