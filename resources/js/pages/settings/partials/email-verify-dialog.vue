<script setup lang="ts">
import { InputField } from '@/components/fields/input-field';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import { PinInput, PinInputSlot } from '@/components/ui/pin-input';
import { onSubmitEmail, onSubmitVerifyCode } from '@/lib/verify-email';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { open, setOpen } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const scene = ref<'email' | 'verify_code'>('email');

const emailForm = useForm({ email: '' });
const verifyCodeForm = useForm({ code: [] });
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent>
            <DialogTitle>Verify your E-mail</DialogTitle>
            <DialogDescription>
                Enter your E-mail below and we will send you secret code
            </DialogDescription>

            <!-- email scene -->
            <form
                v-if="scene === 'email'"
                @submit.prevent="
                    () => {
                        onSubmitEmail({
                            action: 'verify',
                            form: emailForm,
                        });
                        scene = 'verify_code';
                    }
                "
                class="space-y-6"
            >
                <InputField
                    v-model="emailForm.email"
                    :error-message="emailForm.errors.email"
                    autocomplete="email"
                    label="Email"
                />

                <div class="flex gap-4">
                    <Button
                        @click="() => setOpen(false)"
                        type="button"
                        variant="secondary"
                        >Cancel</Button
                    >
                    <Button :disabled="emailForm.processing">Send Code</Button>
                </div>
            </form>

            <!-- verify_code scene -->
            <form
                v-else
                @submit.prevent="
                    () => {
                        onSubmitVerifyCode({
                            action: 'verify',
                            form: verifyCodeForm,
                        });
                        setOpen(false);
                        scene = 'email';
                    }
                "
                class="space-y-6"
            >
                <PinInput
                    id="otp"
                    placeholder="○"
                    otp
                    v-model="verifyCodeForm.code"
                >
                    <PinInputSlot
                        v-for="(id, index) in 6"
                        :key="id"
                        :index="index"
                        :disabled="emailForm.processing"
                        autofocus
                    />
                </PinInput>
                <InputError
                    :message="verifyCodeForm.errors.code"
                    class="mt-2"
                />

                <div class="flex gap-4">
                    <Button
                        @click="() => setOpen(false)"
                        type="button"
                        variant="secondary"
                        >Cancel</Button
                    >
                    <Button :disabled="emailForm.processing">Verify</Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
