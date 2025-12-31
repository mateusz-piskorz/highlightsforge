<script setup lang="ts">
import NewPostBtn from '@/components/new-post-btn.vue';
import PostSearchInput from '@/components/post-search-input.vue';
import PostSortInput from '@/components/post-sort-input.vue';
</script>

<template>
    <div class="mx-auto max-w-[900px]">
        <div class="my-10 space-y-5 px-5 lg:px-0">
            <PostSearchInput className="md:hidden" />
            <NewPostBtn />
            <PostSortInput />
        </div>
    </div>
</template>
