<script setup lang="ts">
import { useCommonDialogs } from '@/lib/composables/useCommonDialogs';
import { Link, usePage } from '@inertiajs/vue3';
import Button from './ui/button/Button.vue';

const { user } = usePage().props.auth;
const { setOpen } = useCommonDialogs();
</script>

<template>
    <Button v-if="user" variant="secondary" as-child>
        <Link href="/profile">Profile</Link>
    </Button>
    <Button v-if="!user" variant="secondary" @click="() => setOpen('login')">Login</Button>
</template>
