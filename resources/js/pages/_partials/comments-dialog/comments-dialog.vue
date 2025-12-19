<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import Separator from '@/components/ui/separator/Separator.vue';
import { provide, ref } from 'vue';
import CommentForm from './comment-form.vue';
import CommentsList from './comments-list.vue';

const { open, setOpen, clipId } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
    clipId: string;
}>();

type ActiveComment = { parentId: string | null; id: string };

const activeComment = ref<ActiveComment[] | null>(null);

const setActiveComment = (val: ActiveComment) => {
    activeComment.value = activeComment.value
        ? [...activeComment.value, val]
        : [val];
};

const goBack = () => {
    activeComment.value = activeComment.value
        ? activeComment.value?.slice(0, -1)
        : null;
};

provide('comments', {
    activeComment,
    setActiveComment,
    goBack,
    clipId,
});
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent class="space-y-8 px-0">
            <DialogTitle class="px-5">Comments</DialogTitle>
            <DialogDescription class="px-5"> description </DialogDescription>

            <Separator />
            <CommentForm />
            <CommentsList />
        </DialogContent>
    </Dialog>
</template>
