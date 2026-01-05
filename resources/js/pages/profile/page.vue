<script setup lang="ts">
import Heading from '@/components/heading.vue';
import { PostList } from '@/components/post-list';
import PostsActions from '@/components/posts-actions.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { user } = usePage().props.auth;
</script>

<template>
    <Head title="User settings" />
    <AppLayout>
        <div class="w-full gap-y-12 py-12">
            <Heading title="Posts" description="Manage your posts and see details" class="px-5 sm:px-6 md:px-8 lg:px-10" />

            <PostsActions className="px-5 sm:px-6 md:px-8 lg:px-10" />
            <PostList editMode :authorId="user?.id" className=" sm:px-6 md:px-8 lg:px-10" />
        </div>
    </AppLayout>
</template>
