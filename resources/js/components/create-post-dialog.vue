<script setup lang="ts">
import { InputField } from '@/components/fields/input-field';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { useForm } from '@inertiajs/vue3';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { toast } from 'vue-sonner';
import TextareaField from './fields/textarea-field/textarea-field.vue';

const queryClient = useQueryClient();

const { open, setOpen } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const form = useForm({ title: '', file: null, description: '' });

const submit = async () => {
    const formData = new FormData();
    formData.append('file', form.file as unknown as File);
    formData.append('title', form.title);
    formData.append('description', form.description);
    const { data } = await axios.post('/api/posts', formData);

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(data.message);
    await queryClient.refetchQueries({ queryKey: ['posts'] });
    setOpen(false);
};
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent>
            <DialogTitle>Create a Post</DialogTitle>
            <DialogDescription> Create a Post description </DialogDescription>

            <form @submit.prevent="submit" class="space-y-6">
                <InputField v-model="form.title" :error-message="form.errors.title" label="Title" />
                <TextareaField
                    v-model="form.description"
                    :error-message="form.errors.description"
                    autofocus=""
                    placeholder="Description (optional)"
                    class="max-h-[150px] resize-none"
                />

                <InputField
                    type="file"
                    accept="video/mp4,video/webm,image/jpeg,image/png,image/webp"
                    @change="(e: any) => (form.file = e.target.files?.[0])"
                    :error-message="form.errors.file"
                    label="File"
                />

                <div class="flex gap-4">
                    <Button @click="() => setOpen(false)" type="button" variant="secondary">Cancel</Button>
                    <Button :disabled="form.processing">Create Post</Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
