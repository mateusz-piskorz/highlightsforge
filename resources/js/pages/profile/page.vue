<script setup lang="ts">
import Heading from '@/components/heading.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="User settings" />
    <AppLayout>
        <div class="flex w-full flex-col gap-y-12 py-12">
            <div class="flex flex-col items-start space-y-6 px-5 sm:px-6 md:px-8 lg:px-10">
                <Heading title="Profile" description="Profile description" />
                something here
            </div>
        </div>
    </AppLayout>
</template>
