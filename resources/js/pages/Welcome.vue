<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import LoginDialog from './_partials/login-dialog.vue';
import RegisterDialog from './_partials/register-dialog.vue';

const open = ref<null | 'register' | 'login'>(null);
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

    <AppLayout>
        <div class="space-x-4">
            <Button @click="() => (open = 'register')">Register</Button>
            <Button @click="() => (open = 'login')">Login</Button>
        </div>
    </AppLayout>
</template>
