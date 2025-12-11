import { InertiaForm } from '@inertiajs/vue3';
import axios from 'axios';
import { toast } from 'vue-sonner';
import { setFormApiErrors } from './set-form-api-errors';

const step1Url = { verify: '/api/verifyEmailStep1', login: '/api/loginStep1' };
const step2Url = { verify: '/api/verifyEmailStep2', login: '/api/loginStep2' };
const verifyCodeMsg = {
    login: 'logged in successfully',
    verify: 'email verified successfully',
};

type Args = {
    form: InertiaForm<any>;
    action: 'verify' | 'login';
};

export const onSubmitEmail = async ({ form, action }: Args) => {
    const { data } = await axios.post(step1Url[action], form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success('verify code sent successfully');
};

export const onSubmitVerifyCode = async ({ form, action }: Args) => {
    const { data } = await axios.post(step2Url[action], form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(verifyCodeMsg[action]);
};
