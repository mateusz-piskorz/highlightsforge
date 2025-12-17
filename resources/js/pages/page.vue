<script setup lang="ts">
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import ClipList from './_partials/clip-list.vue';
import Sidebar from './_partials/sidebar.vue';
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <div
            class="relative items-start gap-4 space-y-20 lg:flex lg:justify-between lg:gap-15 lg:px-6"
        >
            <Sidebar />
            <ClipList />
        </div>
    </AppLayout>
</template>
