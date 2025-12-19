<script setup lang="ts">
import ConfirmDialog from '@/components/confirm-dialog.vue';
import Button from '@/components/ui/button/Button.vue';
import UserAvatar from '@/components/user-avatar.vue';
import { cn } from '@/lib/utils';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { PenSquare } from 'lucide-vue-next';
import { computed, inject, ref } from 'vue';
import { toast } from 'vue-sonner';
import CommentActions from './comment-actions.vue';
import CommentForm from './comment-form.vue';
import CommentsList from './comments-list.vue';

const queryClient = useQueryClient();

const {
    className,
    id,
    author,
    content,
    deleted,
    isOwner,
    updatedAt,
    replies_count,
    nestLevel,
    parentId,
    upvoted,
    upvotes_count,
} = defineProps<{
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

const { activeComment, setActiveComment, goBack, clipId } = inject('comments');

const maxLength = 225;

const editing = ref(false);
const isExpanded = ref(false);
const showReplyForm = ref(false);
const showReplies = ref(false);
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
            <UserAvatar
                :name="author.name"
                :src="author.avatar"
                :commentedAt="updatedAt"
                :youIndicator="isOwner"
            />

            <Button
                v-if="isOwner"
                variant="ghost"
                @click="() => (editing = !editing)"
            >
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

        <p v-if="!editing" :class="cn('font-sm', deleted && 'line-through')">
            {{
                deleted
                    ? '(comment removed)'
                    : !isTooLong || isExpanded
                      ? content
                      : `${content.substring(0, maxLength)}...`
            }}
            <Button
                v-if="isTooLong && !isExpanded"
                class="pl-1 text-muted-foreground"
                @click="isExpanded = true"
                variant="link"
            >
                more
            </Button>
        </p>

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
                    const { data } = await axios.post(
                        `/api/comments/${id}/upvote`,
                    );

                    if (!data.success) {
                        toast.error(data.message);
                        return;
                    }

                    await queryClient.refetchQueries({
                        queryKey: ['comments', clipId, parentId],
                    });

                    toast.success(data.message);

                    upvoteDisabled = false;
                }
            "
            @removeEvent="() => (confirmOpen = true)"
            @replyEvent="
                () => {
                    if (nestLevel >= 2) {
                        setActiveComment({ id, parentId });
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
                        setActiveComment({ id, parentId });
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

        <CommentsList
            v-if="showReplies"
            :nestLevel="nestLevel + 1"
            :parentId="id"
        />
    </div>
</template>
