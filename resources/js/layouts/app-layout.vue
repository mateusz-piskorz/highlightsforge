<script setup lang="ts">
import { Header } from '@/components/header';
import LoginDialog from '@/components/login-dialog.vue';
import PostDialog from '@/components/post-dialog.vue';
import RegisterDialog from '@/components/register-dialog.vue';
import SettingsSidebar from '@/components/settings-sidebar.vue';
import { usePage } from '@inertiajs/vue3';
import { provide, ref } from 'vue';

type DialogType = 'register' | 'login' | 'post';

const open = ref<null | DialogType>(null);
const setOpen = (val: DialogType) => (open.value = val);

provide('common-dialogs', { setOpen });

const { url } = usePage();
</script>

<template>
    <RegisterDialog :open="open === 'register'" :set-open="(val) => (open = val ? 'register' : null)" :login-action="() => (open = 'login')" />
    <LoginDialog :open="open === 'login'" :set-open="(val) => (open = val ? 'login' : null)" :register-action="() => (open = 'register')" />
    <PostDialog :open="open === 'post'" :set-open="(val) => (open = val ? 'post' : null)" />

    <Header />
    <main class="mx-auto min-h-[100vh] max-w-7xl pt-[68px]">
        <div v-if="['/profile', '/settings'].includes(url)" class="flex h-full">
            <SettingsSidebar
                class="sticky top-[68px] hidden h-[calc(100vh-68px)] min-w-[155px] border-r px-6 pt-12 md:flex lg:min-w-[195px] lg:px-10"
            />
            <slot />
        </div>

        <slot v-else />
    </main>
</template>
