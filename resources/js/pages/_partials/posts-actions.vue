<script setup lang="ts">
import NewPostBtn from '@/components/new-post-btn.vue';
import PostSortInput from '@/components/post-sort-input.vue';
import { useCommonDialogs } from '@/lib/composables/useCommonDialogs';
import { usePage } from '@inertiajs/vue3';

const { user } = usePage().props.auth;

const { setOpen } = useCommonDialogs();
</script>

<template>
    <div class="mx-auto max-w-[900px]">
        <div class="my-10 space-y-5 px-5 lg:px-0">
            <NewPostBtn />
            <PostSortInput />
        </div>
    </div>
</template>
