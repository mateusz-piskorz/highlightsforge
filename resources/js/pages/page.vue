<script setup lang="ts">
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, provide } from 'vue';
import { PostList } from './_partials/post-list';
import { CommentsDialog } from './_partials/comments-dialog';
import { Header } from './_partials/header';
import { SortOptions } from '@/lib/composables/usePostFilters';
import PostsActions from './_partials/posts-actions.vue';

const selectedPost = ref<{ id: number; totalResponses: number } | null>(null);

const q = ref<string | null>(null);
const setQ = (val: string) => (q.value = val);

const sorting = ref<SortOptions>('featured');
const setSorting = (val: SortOptions) => (sorting.value = val);

provide('post-filters', { q, setQ, sorting, setSorting });
</script>

<template>
    <Head title="Home" />
    <Header />

    <AppLayout>
        <PostsActions />
        <PostList @commentsEvent="(args: { id: number; totalResponses: number }) => (selectedPost = args)" />
    </AppLayout>

    <CommentsDialog
        v-if="selectedPost"
        :totalResponses="selectedPost.totalResponses"
        :open="Boolean(selectedPost)"
        :setOpen="(val) => (selectedPost = val ? selectedPost : null)"
        :postId="selectedPost.id"
    />
</template>
