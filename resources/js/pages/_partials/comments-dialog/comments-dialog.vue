<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { usePage } from '@inertiajs/vue3';
import { useQueryClient } from '@tanstack/vue-query';
import { ChevronLeft } from 'lucide-vue-next';
import { computed, provide, ref } from 'vue';
import CommentForm from './comment-form.vue';
import Comment from './comment.vue';
import CommentsList from './comments-list.vue';

const { user } = usePage().props.auth;

const queryClient = useQueryClient();

const { clipId, open, setOpen, totalResponses } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
    clipId: number;
    totalResponses: number;
}>();

type ActiveComment = { parentId: number | null; id: number };

const activeThread = ref<ActiveComment[] | null>(null);

const setActiveThread = (val: ActiveComment) => {
    activeThread.value = activeThread.value ? [...activeThread.value, val] : [val];
};

const goBack = () => {
    activeThread.value = activeThread.value ? activeThread.value?.slice(0, -1) : null;
};

provide('comments-dialog', {
    activeThread,
    setActiveThread,
    goBack,
    clipId: computed(() => clipId),
});

const activeComment = computed(() => {
    if (!activeThread.value || activeThread.value?.length === 0) return false;
    const { parentId, id } = activeThread.value[activeThread.value?.length - 1];
    const { data } = queryClient.getQueryData(['comments', clipId, parentId]) as any;

    return data.find((e: any) => e.id === id);
});
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent class="space-y-8 px-0">
            <DialogTitle v-if="!activeComment" class="px-5">Responses ({{ totalResponses }})</DialogTitle>
            <div v-if="activeComment" class="flex items-center px-5">
                <Button @click="goBack" variant="ghost" class="relative mr-2 px-1 pl-0">
                    <span class="sr-only">go back</span>
                    <ChevronLeft class="size-6" />
                </Button>
                Replies
            </div>

            <CommentForm v-if="!activeComment" />
            <Comment
                v-if="activeComment"
                :showRepliesInit="true"
                :upvoted="activeComment.upvoted"
                :upvotes_count="activeComment.upvotes_count"
                :deleted="activeComment.deleted"
                :parentId="activeComment.parent_id"
                :nestLevel="0"
                :key="activeComment.id"
                :id="activeComment.id"
                :replies_count="activeComment.replies_count"
                :content="activeComment.content"
                :author="{
                    avatar: activeComment.user.avatar,
                    name: activeComment.user.user_name,
                }"
                :isOwner="user?.id === activeComment.user_id"
                :updatedAt="activeComment.updated_at"
            />
            <CommentsList v-if="!activeComment" :parentId="null" />
        </DialogContent>
    </Dialog>
</template>
