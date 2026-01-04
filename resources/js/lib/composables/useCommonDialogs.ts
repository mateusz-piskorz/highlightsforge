import { inject } from 'vue';

export type DialogType = 'register' | 'login' | 'post';
export type SelectedPost = {
    id: number;
    totalResponses: number;
} | null;

export const useCommonDialogs = () => {
    const obj = inject<{
        setOpen: (val: DialogType) => DialogType;
        setSelectedPost: (post: SelectedPost) => void;
    }>('common-dialogs');

    if (!obj) throw new Error('useCommonDialogs must be used inside common-dialogs provider');

    return obj;
};
