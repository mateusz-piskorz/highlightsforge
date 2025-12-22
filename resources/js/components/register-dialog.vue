<script setup lang="ts">
import { InputField } from '@/components/fields/input-field';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { toast } from 'vue-sonner';

const { open, setOpen, loginAction } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
    loginAction: () => void;
}>();

const form = useForm({ user_name: '' });

const onSubmit = async () => {
    const { data } = await axios.post('/api/register', form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success('account created successfully');
    router.reload();
    setOpen(false);
};
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent>
            <DialogTitle>Create Account</DialogTitle>
            <DialogDescription> Create account by entering your User Name below, E-mail can be verified later </DialogDescription>

            <form @submit.prevent="onSubmit" class="space-y-6">
                <InputField v-model="form.user_name" :error-message="form.errors.user_name" autocomplete="off" label="User Name" />

                <div class="flex gap-4">
                    <Button @click="() => setOpen(false)" type="button" variant="secondary">Cancel</Button>
                    <Button :disabled="form.processing">Register</Button>
                </div>
            </form>

            <div class="flex items-center">
                <p>Already have an account?</p>
                <Button type="button" @click="loginAction" variant="link">Sign up Now</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
