<script setup lang="ts">
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, provide } from 'vue';
import PostList from './_partials/post-list.vue';
import { CommentsDialog } from './_partials/comments-dialog';
import { Header } from './_partials/header';

const selectedPost = ref<{ id: number; totalResponses: number } | null>(null);

const q = ref<string | null>(null);

const setQ = (val: string) => (q.value = val);

provide('post-filters', { q, setQ });
</script>

<template>
    <Head title="Home" />
    <CommentsDialog
        v-if="selectedPost"
        :totalResponses="selectedPost.totalResponses"
        :open="Boolean(selectedPost)"
        :setOpen="(val) => (selectedPost = val ? selectedPost : null)"
        :postId="selectedPost.id"
    />

    <Header />

    <AppLayout>
        <PostList
            @commentsEvent="
                (args: { id: number; totalResponses: number }) => {
                    selectedPost = args;
                    console.log('here123');
                }
            "
        />
    </AppLayout>
</template>
