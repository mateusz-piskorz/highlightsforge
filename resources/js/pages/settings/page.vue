<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout.vue';
import { Head, usePage } from '@inertiajs/vue3';

import AppearanceSection from './partials/appearance-section.vue';
import AvatarSection from './partials/avatar-section.vue';
import DeleteAccountSection from './partials/delete-account-section.vue';
import ProfileSection from './partials/profile-section.vue';

const { user } = usePage().props.auth;
</script>

<template>
    <Head title="User settings" />
    <AppLayout>
        <div class="flex w-full flex-col space-y-12 py-12">
            <template v-if="user">
                <AppearanceSection />
                <Separator />
                <ProfileSection />
                <Separator />
                <AvatarSection />
                <Separator />
                <DeleteAccountSection />
            </template>
            <h1 class="px-4 text-2xl text-destructive" v-else>Unauthorized</h1>
        </div>
    </AppLayout>
</template>
