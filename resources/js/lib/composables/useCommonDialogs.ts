import { inject } from 'vue';

type DialogType = 'register' | 'login' | 'post';

export const useCommonDialogs = () => {
    const obj = inject<{
        setOpen: (val: DialogType) => DialogType;
    }>('common-dialogs');

    if (!obj) throw new Error('useCommonDialogs must be used inside common-dialogs provider');

    return obj;
};
