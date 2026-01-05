<script setup lang="ts">
import TextareaField from '@/components/fields/textarea-field/textarea-field.vue';
import Button from '@/components/ui/button/Button.vue';
import { useCommentsDialog } from '@/lib/composables/useCommentsDialog';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { cn } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { toast } from 'vue-sonner';

const queryClient = useQueryClient();

const { postId } = useCommentsDialog();

const { initContent, parentId, commentId, onCancel, onSuccess, className } = defineProps<{
    initContent?: string;
    commentId?: number;
    parentId: number | null;
    onCancel?: () => void;
    onSuccess?: () => void;
    className?: string;
}>();

const form = useForm({ content: initContent || '' });

const submit = async () => {
    let data;

    if (commentId) {
        data = (
            await axios.put(`/api/comments/${commentId}`, {
                ...form.data(),
                post_id: postId.value,
                parent_id: parentId,
            })
        ).data;
    } else {
        data = (
            await axios.post('/api/comments', {
                ...form.data(),
                post_id: postId.value,
                parent_id: parentId,
            })
        ).data;
    }

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(data.message);
    await queryClient.refetchQueries({
        queryKey: ['comments', postId.value, parentId],
    });
    onSuccess?.();
};
</script>

<template>
    <form @submit.prevent="submit" :class="cn('space-y-6 px-5', className)">
        <div className="dark:bg-input/30 rounded-md border">
            <TextareaField
                v-model="form.content"
                :error-message="form.errors.content"
                autofocus=""
                placeholder="What are your thoughts?"
                class="max-h-[150px] resize-none border-none shadow-none dark:bg-transparent"
            />

            <div className="flex justify-end gap-2 p-4">
                <Button
                    type="button"
                    @click="
                        () => {
                            form.reset('content');
                            onCancel?.();
                        }
                    "
                >
                    Cancel
                </Button>
                <Button :disabled="form.processing" type="submit" variant="secondary">
                    {{ initContent ? 'Save' : 'Respond' }}
                </Button>
            </div>
        </div>
    </form>
</template>
