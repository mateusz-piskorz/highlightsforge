<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ClipList from './_partials/clip-list.vue';
import CommentsDialog from './_partials/comments-dialog/comments-dialog.vue';
import Sidebar from './_partials/sidebar.vue';

const open = ref(false);
</script>

<template>
    <Head title="Home" />
    <CommentsDialog
        :open="open"
        :setOpen="(val) => (open = val)"
        :clipId="23"
    />

    <AppLayout>
        <Button @click="() => (open = true)">Open comments sheet</Button>
        <div
            class="relative items-start gap-4 space-y-20 lg:flex lg:justify-between lg:gap-15 lg:px-6"
        >
            <Sidebar />
            <ClipList />
        </div>
    </AppLayout>
</template>
