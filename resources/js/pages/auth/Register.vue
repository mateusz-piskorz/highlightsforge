<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';

import { login } from '@/routes';
import { Head, router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import axios from 'axios';
import { useForm, Field as VeeField } from 'vee-validate';
import { z } from 'zod';

const { handleSubmit } = useForm({
    validationSchema: toTypedSchema(
        z.object({
            user_name: z
                .string()
                .min(3, 'Username must be at least 3 characters.')
                .max(50, 'Username must be at most 50 characters.'),
        }),
    ),
});

const onSubmit = handleSubmit(async ({ user_name }) => {
    await axios.post('/api/registerViaUsername', {
        user_name,
    });

    router.visit('/settings');
});
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <form id="form-vee-demo" @submit="onSubmit">
            <VeeField v-slot="{ field, errorMessage }" name="user_name">
                <Field :data-invalid="!!errorMessage">
                    <FieldLabel for="form-vee-demo-file">Username</FieldLabel>
                    <Input
                        id="form-vee-demo-file"
                        autocomplete="off"
                        :aria-invalid="!!errorMessage"
                        @change="field.onChange"
                    />
                    <FieldError
                        v-if="errorMessage"
                        :errors="[{ message: errorMessage }]"
                    />
                </Field>
            </VeeField>
            <Button type="submit">Dwa</Button>
        </form>
        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="6"
                >Log in</TextLink
            >
        </div>
    </AuthBase>
</template>
