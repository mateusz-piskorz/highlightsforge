<script setup lang="ts">
import CreatePostDialog from '@/components/create-post-dialog.vue';
import NewPostBtn from '@/components/new-post-btn.vue';
import PostSortInput from '@/components/post-sort-input.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { user } = usePage().props.auth;

const open = ref<null | 'register' | 'login' | 'post'>(null);
</script>

<template>
    <CreatePostDialog :open="open === 'post'" :set-open="(val) => (open = val ? 'post' : null)" />
    <div class="mx-auto max-w-[900px]">
        <div class="my-10 space-y-5 px-5 lg:px-0">
            <NewPostBtn @click="() => (open = user ? 'post' : 'login')" />
            <PostSortInput />
        </div>
    </div>
</template>
