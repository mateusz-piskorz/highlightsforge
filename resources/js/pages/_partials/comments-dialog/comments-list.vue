<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import SpinLoader from '@/components/ui/spin-loader.vue';
import { useCommentsDialog } from '@/lib/composables/useCommentsDialog';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { useInfiniteQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { computed } from 'vue';
import Comment from './comment.vue';

const { user } = usePage().props.auth;

const { parentId, nestLevel } = defineProps<{
    parentId: number | null;
    nestLevel?: number;
}>();

const { postId } = useCommentsDialog();

const { data, refetch, fetchNextPage, hasNextPage, isFetchingNextPage, isPending } = useInfiniteQuery({
    queryKey: ['comments', postId.value, parentId],
    queryFn: async ({ pageParam }) => (await axios.get('api/comments', { params: { parentId, postId: postId.value, page: pageParam } })).data,
    getNextPageParam: (lastPage) => (lastPage.next_page_url ? lastPage.current_page + 1 : undefined),
    initialPageParam: 1,
});

const results = computed(() => data.value?.pages.flatMap((page) => page.data) ?? []);
</script>

<template>
    <div :class="cn('space-y-8', nestLevel && 'ml-2 border-l-2 pl-2')">
        <Comment
            v-for="(comment, index) in results"
            :upvoted="comment.upvoted"
            :upvotes_count="comment.upvotes_count"
            :deleted="comment.deleted"
            :parentId="comment.parent_id"
            :nestLevel="nestLevel || 0"
            :key="comment.id"
            :id="comment.id"
            :replies_count="comment.replies_count"
            :content="comment.content"
            :author="{
                avatar: comment.user.avatar,
                name: comment.user.user_name,
            }"
            :isOwner="user?.id === comment.user_id"
            :updatedAt="comment.updated_at"
            :className="index !== 0 ? 'border-t pt-8' : ''"
        />
        <SpinLoader v-if="isFetchingNextPage || isPending" />
        <Button v-if="hasNextPage && !isFetchingNextPage" @click="() => hasNextPage && !isFetchingNextPage && fetchNextPage()">Load more...</Button>
    </div>
</template>
