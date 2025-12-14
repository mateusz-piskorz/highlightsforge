import { InertiaForm } from '@inertiajs/vue3';
import axios from 'axios';
import { toast } from 'vue-sonner';
import { setFormApiErrors } from './set-form-api-errors';

const step1Url = {
    verify: '/api/verify-email-step-1',
    login: '/api/login-step-1',
};
const step2Url = {
    verify: '/api/verify-email-step-2',
    login: '/api/login-step-2',
};
const verifyCodeMsg = {
    login: 'logged in successfully',
    verify: 'email verified successfully',
};

type Args = {
    form: InertiaForm<any>;
    action: 'verify' | 'login';
    onSuccess?: () => void;
};

export const onSubmitEmail = async ({ form, action, onSuccess }: Args) => {
    const { data } = await axios.post(step1Url[action], form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success('verify code sent successfully');
    onSuccess?.();
};

export const onSubmitVerifyCode = async ({ form, action, onSuccess }: Args) => {
    const { data } = await axios.post(step2Url[action], form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(verifyCodeMsg[action]);
    onSuccess?.();
};
