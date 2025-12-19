<script setup lang="ts">
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { inject } from 'vue';
import Comment from './comment.vue';

const { user } = usePage().props.auth;

const { parentId, nestLevel } = defineProps<{
    parentId?: number;
    nestLevel?: number;
}>();

const { clipId } = inject('comments');

const { data } = useQuery({
    queryKey: ['comments', clipId, parentId],
    queryFn: async () =>
        (await axios.get('api/comments', { params: { parentId } })).data,
});
</script>

<template>
    <div :class="cn('space-y-8', nestLevel && 'ml-2 border-l-2 pl-2')">
        <Comment
            v-for="(comment, index) in data?.data"
            :upvoted="comment.upvoted"
            :upvotes_count="comment.upvotes_count"
            :deleted="comment.deleted"
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
    </div>
</template>
