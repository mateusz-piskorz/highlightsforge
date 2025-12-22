<script setup lang="ts">
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ClipList from './_partials/clip-list.vue';
import CommentsDialog from './_partials/comments-dialog/comments-dialog.vue';
import Header from './_partials/header.vue';

const selectedClip = ref<{ id: number; totalResponses: number } | null>(null);
</script>

<template>
    <Head title="Home" />
    <CommentsDialog
        v-if="selectedClip"
        :totalResponses="selectedClip.totalResponses"
        :open="Boolean(selectedClip)"
        :setOpen="(val) => (selectedClip = val ? selectedClip : null)"
        :clipId="selectedClip.id"
    />

    <AppLayout>
        <div class="relative items-start gap-4 space-y-20 lg:flex lg:justify-between lg:gap-15 lg:px-6">
            <Header />
            <ClipList
                @commentsEvent="
                    (args: { id: number; totalResponses: number }) => {
                        selectedClip = args;
                        console.log('here123');
                    }
                "
            />
        </div>
    </AppLayout>
</template>
