<script setup lang="ts">
import { InputField } from '@/components/fields/input-field';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { useForm } from '@inertiajs/vue3';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';
import TextareaField from './fields/textarea-field/textarea-field.vue';

const { open, setOpen, post } = defineProps<{
    post?: { title: string; description?: string; id: number } | null;
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const queryClient = useQueryClient();

const form = useForm({ title: '', file: null, description: '' });

const sending = ref(false);
const progress = ref(0);
watchEffect(() => {
    if (post) {
        form.title = post.title;
        form.description = post.description ?? '';
        form.file = null;
    } else {
        form.reset();
    }
});

const submit = async () => {
    sending.value = true;
    const formData = new FormData();
    if (form.file) formData.append('file', form.file as unknown as File);
    formData.append('title', form.title);
    formData.append('description', form.description);

    let data;
    if (post) {
        data = (
            await axios.put(`/api/posts/${post.id}`, formData, {
                onUploadProgress: (progressEvent) => {
                    const loaded = progressEvent.loaded;
                    const total = progressEvent.total || 0;
                    progress.value = Math.round((loaded / total) * 100);
                },
            })
        ).data;
    } else {
        data = (
            await axios.post('/api/posts', formData, {
                onUploadProgress: (progressEvent) => {
                    const loaded = progressEvent.loaded;
                    const total = progressEvent.total || 0;
                    progress.value = Math.round((loaded / total) * 100);
                },
            })
        ).data;
    }

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        sending.value = false;
        return;
    }

    toast.success(data.message);
    await queryClient.refetchQueries({ queryKey: ['posts'] });
    setOpen(false);
    sending.value = false;
};
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent>
            <DialogTitle>{{ post ? 'Update' : 'Create' }} a Post</DialogTitle>
            <DialogDescription>{{ post ? 'Update' : 'Create' }} a Post description</DialogDescription>

            <form v-if="!sending" @submit.prevent="submit" class="space-y-6">
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
                    <Button :disabled="form.processing">{{ post ? 'Update' : 'Create' }} Post</Button>
                </div>
            </form>

            <div v-if="sending">
                <p>Sending...</p>
                <progress max="100" :value.prop="progress" />
            </div>
        </DialogContent>
    </Dialog>
</template>
