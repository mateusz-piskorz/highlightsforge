<script setup lang="ts">
import { InputField } from '@/components/fields/input-field';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { toast } from 'vue-sonner';

const { open, setOpen } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const form = useForm({ title: '', clip: null });

const submit = async () => {
    const formData = new FormData();
    formData.append('clip', form.clip as unknown as File);
    formData.append('title', form.title);
    const { data } = await axios.post('/api/clip', formData);

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(data.message);
};
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent>
            <DialogTitle>Create a highlight clip</DialogTitle>
            <DialogDescription>
                Create a highlight clip description
            </DialogDescription>

            <form @submit.prevent="submit" class="space-y-6">
                <InputField
                    v-model="form.title"
                    :error-message="form.errors.title"
                    label="Title"
                />

                <InputField
                    type="file"
                    accept="video/mp4,video/webm"
                    @change="
                        (e) => {
                            form.clip = e.target.files[0];
                        }
                    "
                    :error-message="form.errors.clip"
                    label="Clip"
                />

                <div class="flex gap-4">
                    <Button
                        @click="() => setOpen(false)"
                        type="button"
                        variant="secondary"
                        >Cancel</Button
                    >
                    <Button :disabled="form.processing">Create Clip</Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
