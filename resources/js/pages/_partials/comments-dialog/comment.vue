<script setup lang="ts">
import CollapsibleText from '@/components/collapsible-text.vue';
import ConfirmDialog from '@/components/confirm-dialog.vue';
import Button from '@/components/ui/button/Button.vue';
import UserAvatar from '@/components/user-avatar.vue';
import { useCommentsDialog } from '@/lib/composables/useCommentsDialog';
import { cn } from '@/lib/utils';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { PenSquare } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import CommentActions from './comment-actions.vue';
import CommentForm from './comment-form.vue';
import CommentsList from './comments-list.vue';

const queryClient = useQueryClient();

const { className, id, author, content, deleted, isOwner, updatedAt, replies_count, nestLevel, parentId, upvoted, upvotes_count, showRepliesInit } =
    defineProps<{
        showRepliesInit?: boolean;
        id: number;
        upvoted: boolean;
        upvotes_count: number;
        parentId?: number;
        nestLevel: number;
        className?: string;
        content: string;
        deleted: boolean | null;
        isOwner: boolean;
        updatedAt: string;
        replies_count: number;
        author: {
            name: string;
            avatar: string | null;
        };
    }>();

const { setActiveThread, postId } = useCommentsDialog();

const maxLength = 225;

const editing = ref(false);
const isExpanded = ref(false);
const showReplyForm = ref(showRepliesInit);
const showReplies = ref(showRepliesInit);
const confirmOpen = ref(false);
const upvoteDisabled = ref(false);

const isTooLong = computed(() => content.length > maxLength);

const removeHandler = async () => {
    const { data } = await axios.delete(`/api/comments/${id}`);

    if (!data.success) {
        toast.error(data.message);
        return;
    }

    await queryClient.refetchQueries({ queryKey: ['comments'] });
    toast.success(data.message);
    confirmOpen.value = false;
};
</script>

<template>
    <ConfirmDialog
        :open="confirmOpen"
        :setOpen="(val) => (confirmOpen = val)"
        title="Remove comment"
        description="Do you really want to remove your Comment? This is a permanent operation and can't be undone."
        btnText="Remove"
        @continueEvent="removeHandler"
    />

    <div :class="cn('space-y-6 px-5 py-6', className)">
        <div class="flex">
            <UserAvatar :name="author.name" :src="author.avatar" :commentedAt="updatedAt" :youIndicator="isOwner" />

            <Button v-if="isOwner" variant="ghost" @click="() => (editing = !editing)">
                <span class="sr-only">edit comment</span>
                <PenSquare class="size-4" />
            </Button>
        </div>

        <CommentForm
            v-if="editing"
            autoFocus
            className="pl-0"
            :commentId="id"
            :initContent="content"
            :onCancel="() => (editing = false)"
            :onSuccess="() => (editing = false)"
        />

        <CollapsibleText :content="deleted ? '(comment removed)' : content" :className="deleted ? 'line-through' : undefined" />

        <CommentActions
            :upvoteDisabled
            :isOwner
            :upvoted
            :upvotes_count
            :deleted
            :replies_count
            @upvoteEvent="
                async () => {
                    upvoteDisabled = true;
                    const { data } = await axios.post(`/api/comments/${id}/upvote`);

                    if (!data.success) {
                        toast.error(data.message);
                        return;
                    }

                    await queryClient.refetchQueries({
                        queryKey: ['comments', postId, parentId],
                    });

                    toast.success(data.message);

                    upvoteDisabled = false;
                }
            "
            @removeEvent="() => (confirmOpen = true)"
            @replyEvent="
                () => {
                    if (nestLevel >= 2) {
                        setActiveThread({ id, parentId: parentId || null });
                        return;
                    }
                    if (showReplyForm) {
                        showReplyForm = false;
                    } else {
                        showReplyForm = true;
                        showReplies = true;
                    }
                }
            "
            @showRepliesEvent="
                () => {
                    if (replies_count === 0) return;
                    if (nestLevel >= 2) {
                        setActiveThread({ id, parentId: parentId || null });
                        return;
                    }
                    showReplies = !showReplies;
                }
            "
        />

        <CommentForm
            v-if="showReplyForm"
            autoFocus
            :parentId="id"
            :onCancel="() => (showReplyForm = false)"
            :onSuccess="() => (showReplyForm = false)"
        />

        <CommentsList v-if="showReplies" :nestLevel="nestLevel + 1" :parentId="id" />
    </div>
</template>
