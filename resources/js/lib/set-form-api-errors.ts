import { InertiaForm } from '@inertiajs/vue3';

type Args = {
    form: InertiaForm<any>;
    data: any;
};

export const setFormApiErrors = ({ form, data }: Args) => {
    if ('errors' in data) {
        for (const errorKey in data.errors) {
            form.setError(errorKey, data.errors[errorKey][0]);
        }
    }
};
