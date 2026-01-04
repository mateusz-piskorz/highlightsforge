<script setup lang="ts">
import NewPostBtn from '@/components/new-post-btn.vue';
import PostSearchInput from '@/components/post-search-input.vue';
import PostSortInput from '@/components/post-sort-input.vue';
import { cn } from '@/lib/utils';

const { className } = defineProps<{ className?: string }>();
</script>

<template>
    <div :class="cn(className)">
        <div class="my-10 space-y-5 lg:px-0">
            <PostSearchInput className="md:hidden" />
            <NewPostBtn />
            <PostSortInput />
        </div>
    </div>
</template>
