<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import UpvotesButton from '@/components/upvotes-button.vue';
import { MessageCircle } from 'lucide-vue-next';

const { deleted, replies_count, upvoted, upvotes_count, upvoteDisabled } =
    defineProps<{
        deleted: boolean | null;
        replies_count: number;
        isOwner?: boolean;
        upvoted: boolean;
        upvotes_count: number;
        upvoteDisabled: boolean;
    }>();
</script>

<template>
    <div class="flex items-center">
        <UpvotesButton
            :active="upvoted"
            @click="$emit('upvoteEvent')"
            :upvotes="upvotes_count"
            :disabled="upvoteDisabled"
        />

        <Button variant="ghost" @click="$emit('showRepliesEvent')">
            <MessageCircle /> {{ replies_count }}
        </Button>

        <Button
            :disabled="Boolean(deleted)"
            variant="ghost"
            @click="$emit('replyEvent')"
        >
            Reply
        </Button>
        <Button
            v-if="isOwner && !deleted"
            class="ml-4"
            variant="destructive"
            @click="$emit('removeEvent')"
        >
            remove
        </Button>
    </div>
</template>
