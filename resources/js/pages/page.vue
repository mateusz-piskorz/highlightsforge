<script setup lang="ts">
import { PostList } from '@/components/post-list';
import PostsActions from '@/components/posts-actions.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <PostsActions className="mx-auto max-w-[900px] px-5 sm:px-6 md:px-8" />
        <PostList className="mx-auto sm:px-6 md:px-8" />
    </AppLayout>
</template>
