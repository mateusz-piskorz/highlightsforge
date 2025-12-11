<script setup lang="ts">
import AppLayout from '@/layouts/app-layout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import RegisterDialog from './_partials/register-dialog.vue';

const open = ref(false);
</script>

<template>
    <Head title="Dashboard" />
    <RegisterDialog :open="open" :set-open="(val) => (open = val)" />

    <AppLayout> here123 </AppLayout>
</template>
