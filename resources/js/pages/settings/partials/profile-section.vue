<script setup lang="ts">
import Heading from '@/components/heading.vue';
import { Button } from '@/components/ui/button';
import { usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import axios from 'axios';
import { useForm } from 'vee-validate';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { z } from 'zod';
import EmailVerifyDialog from './email-verify-dialog.vue';

const emailDialogOpen = ref(false);

const { user_name } = usePage().props.auth.user;

const { handleSubmit, isFieldDirty, resetForm } = useForm({
    validationSchema: toTypedSchema(
        z.object({
            user_name: z
                .string()
                .min(3, 'Username must be at least 3 characters.')
                .max(100, 'Username must be at most 100 characters.'),
        }),
    ),
    initialValues: { user_name },
});

const onSubmit = handleSubmit(async ({ user_name }) => {
    const res = await axios.post('/api/updateProfile', {
        user_name,
    });
    const { message, success } = res.data;

    if (!success) {
        toast.error(message);
        return;
    }

    toast.success(message);
    resetForm({ values: { user_name } });
});
</script>

<template>
    <EmailVerifyDialog
        :open="emailDialogOpen"
        :set-open="(val) => (emailDialogOpen = val)"
    />

    <div class="flex flex-col space-y-6 sm:px-6 md:px-8 lg:px-10">
        <Heading
            title="Update profile information"
            description="Update your profile username"
        />
        <Button
            type="button"
            @click="
                () => {
                    emailDialogOpen = true;
                }
            "
            >Change</Button
        >
        <!-- 
        <form id="form-vee-demo" @submit.prevent="onSubmit" class="space-y-8">
            <VeeField name="user_name" v-slot="{ field, errors }">
                <Field :data-invalid="!!errors.length">
                    <FieldLabel for="form-vee-demo-username"
                        >Username</FieldLabel
                    >

                    <Input
                        :default-value="field.value"
                        v-bind="field"
                        type="text"
                        id="form-vee-demo-username"
                        autocomplete="off"
                        :aria-invalid="!!errors.length"
                    />

                    <FieldError
                        v-if="errors.length"
                        :errors="errors.map((e) => ({ message: e }))"
                    />
                </Field>
            </VeeField>

            <div>
                <FieldLabel>E-mail</FieldLabel>
                <p class="text-muted-foreground">
                    myemail@spoko.pl -
                    <span class="text-green-500">verified</span>
                    <Button
                        type="button"
                        @click="
                            () => {
                                emailDialogOpen = true;
                            }
                        "
                        >Change</Button
                    >
                </p>
            </div>

            <Button :disabled="!isFieldDirty('user_name')">Save</Button>
        </form> -->
    </div>
</template>
