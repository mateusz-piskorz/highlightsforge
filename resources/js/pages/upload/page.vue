<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
// import { Field } from '@/components/ui/field';

// import { toTypedSchema } from '@vee-validate/zod';
// import { useForm, Field as VeeField } from 'vee-validate';
// import { z } from 'zod';

// const { handleSubmit, resetForm } = useForm({
//     validationSchema: toTypedSchema(
//         z.object({
//             description: z
//                 .string()
//                 .min(5, 'Description must be at least 20 characters.')
//                 .max(100, 'Description must be at most 100 characters.'),
//             file: z.any(),
//         }),
//     ),
// });

// const onSubmit = handleSubmit(async (data) => {
//     const formData = new FormData();
//     formData.append('clip', data.file);
//     formData.append('description', data.description);

//     const res = await fetch('/api/clip', {
//         method: 'POST',
//         body: formData,
//         credentials: 'same-origin',
//     });

//     console.log(res);
// });
</script>

<template>
    <Card class="mx-auto mt-20 w-full sm:max-w-md">
        <CardHeader>
            <CardTitle>Bug Report</CardTitle>
            <CardDescription>
                Help us improve by reporting bugs you encounter.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <!--             
 
            <form id="form-vee-demo" @submit="onSubmit">
                <FieldGroup>
                    <VeeField v-slot="{ field, errors }" name="description">
                        <Field :data-invalid="!!errors.length">
                            <FieldLabel for="form-vee-demo-description">
                                Description
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupTextarea
                                    id="form-vee-demo-description"
                                    v-bind="field"
                                    placeholder="I'm having an issue with the login button on mobile."
                                    :rows="6"
                                    class="min-h-24 resize-none"
                                    :aria-invalid="!!errors.length"
                                />
                                <InputGroupAddon align="block-end">
                                    <InputGroupText class="tabular-nums">
                                        {{ field.value?.length || 0 }}/100
                                        characters
                                    </InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <FieldDescription>
                                Include steps to reproduce, expected behavior,
                                and what actually happened.
                            </FieldDescription>
                            <FieldError
                                v-if="errors.length"
                                :errors="errors.map((e) => ({ message: e }))"
                            />
                        </Field>
                    </VeeField>
                </FieldGroup>

                <FieldGroup>
                    <VeeField v-slot="{ errors, field }" name="file">
                        <Field :data-invalid="!!errors.length">
                            <FieldLabel for="form-vee-demo-file">
                                File
                            </FieldLabel>
                            <Input
                                type="file"
                                id="form-vee-demo-file"
                                autocomplete="off"
                                :aria-invalid="!!errors.length"
                                @change="field.onChange"
                            />
                            <FieldError
                                v-if="errors.length"
                                :errors="errors.map((e) => ({ message: e }))"
                            />
                        </Field>
                    </VeeField>
                </FieldGroup>
            </form>

        -->
        </CardContent>
        <CardFooter>
            <Field orientation="horizontal">
                <!-- <Button type="button" variant="outline" @click="resetForm">
                    Reset
                </Button> -->
                <Button type="submit" form="form-vee-demo">Submit</Button>
            </Field>
        </CardFooter>
    </Card>
</template>
