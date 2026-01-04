<script setup lang="ts">
import { PostList } from '@/components/post-list';
import PostsActions from '@/components/posts-actions.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <PostsActions className="mx-auto max-w-[900px]" />
        <PostList className="mx-auto" />
    </AppLayout>
</template>
