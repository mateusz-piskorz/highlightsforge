<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ClipList from './_partials/clip-list.vue';
import CreateClipDialog from './_partials/create-clip-dialog.vue';
import LoginDialog from './_partials/login-dialog.vue';
import RegisterDialog from './_partials/register-dialog.vue';

const open = ref<null | 'register' | 'login' | 'clip'>(null);
</script>

<template>
    <Head title="Home" />
    <RegisterDialog
        :open="open === 'register'"
        :set-open="(val) => (open = val ? 'register' : null)"
    />
    <LoginDialog
        :open="open === 'login'"
        :set-open="(val) => (open = val ? 'login' : null)"
    />
    <CreateClipDialog
        :open="open === 'clip'"
        :set-open="(val) => (open = val ? 'clip' : null)"
    />

    <AppLayout>
        <div
            class="relative items-start gap-4 space-y-20 lg:flex lg:justify-between lg:px-6"
        >
            <div
                class="mx-auto space-x-4 lg:sticky lg:top-20 lg:flex lg:flex-col"
            >
                <Button @click="() => (open = 'register')">Register</Button>
                <Button @click="() => (open = 'login')">Login</Button>
                <Button @click="() => (open = 'clip')">Upload a Clip</Button>
            </div>
            <ClipList />
        </div>
    </AppLayout>
</template>
