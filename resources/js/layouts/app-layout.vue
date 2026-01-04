<script setup lang="ts">
import { CommentsDialog } from '@/components/comments-dialog';
import { Header } from '@/components/header';
import LoginDialog from '@/components/login-dialog.vue';
import PostDialog from '@/components/post-dialog.vue';
import RegisterDialog from '@/components/register-dialog.vue';
import SettingsSidebar from '@/components/settings-sidebar.vue';
import { DialogType, SelectedPost } from '@/lib/composables/useCommonDialogs';
import { SortOptions } from '@/lib/composables/usePostFilters';
import { usePage } from '@inertiajs/vue3';
import { provide, ref } from 'vue';

const q = ref<string | null>(null);
const setQ = (val: string) => (q.value = val);

const sorting = ref<SortOptions>('featured');
const setSorting = (val: SortOptions) => (sorting.value = val);

provide('post-filters', { q, setQ, sorting, setSorting });

const open = ref<null | DialogType>(null);
const setOpen = (val: DialogType) => (open.value = val);
const selectedPost = ref<SelectedPost>(null);

provide('common-dialogs', {
    setOpen,
    setSelectedPost: (post: SelectedPost) => (selectedPost.value = post),
});

const { url } = usePage();
</script>

<template>
    <RegisterDialog :open="open === 'register'" :set-open="(val) => (open = val ? 'register' : null)" :login-action="() => (open = 'login')" />
    <LoginDialog :open="open === 'login'" :set-open="(val) => (open = val ? 'login' : null)" :register-action="() => (open = 'register')" />
    <PostDialog :open="open === 'post'" :set-open="(val) => (open = val ? 'post' : null)" />
    <CommentsDialog
        v-if="selectedPost"
        :totalResponses="selectedPost.totalResponses"
        :open="Boolean(selectedPost)"
        :setOpen="(val) => (selectedPost = val ? selectedPost : null)"
        :postId="selectedPost.id"
    />

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
