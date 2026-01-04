<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import UpvotesButton from '@/components/upvotes-button.vue';
import { MessageCircle } from 'lucide-vue-next';

const { comments_count, isOwner, upvoted, upvotes_count, upvoteDisabled } = defineProps<{
    comments_count: number;
    isOwner?: boolean;
    upvoted: boolean;
    upvotes_count: number;
    upvoteDisabled: boolean;
}>();
console.log({ isOwner });
</script>

<template>
    <div class="flex items-center justify-between gap-20 px-4">
        <div class="flex items-center">
            <UpvotesButton :active="upvoted" @click="$emit('upvoteEvent')" :upvotes="upvotes_count" :disabled="upvoteDisabled" />

            <Button @click="$emit('commentsClick')" variant="ghost" class="relative flex items-center gap-2 text-lg">
                <span class="sr-only">comments</span>
                <MessageCircle class="size-4.5" />
                {{ comments_count }}
            </Button>
        </div>
    </div>
</template>
