<script setup lang="ts">
import AppLogo from '@/components/app-logo.vue';
import AppearanceSelect from '@/components/appearance-select.vue';
import ProfileBtn from '@/components/profile-btn.vue';
import Button from '@/components/ui/button/Button.vue';
import { usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';
import { ref } from 'vue';
import PostSearchInput from '../post-search-input.vue';
import MobileMenu from './mobile-menu.vue';

const openMenu = ref(false);

const { url } = usePage();
</script>

<template>
    <MobileMenu :open="openMenu" :set-open="(val) => (openMenu = val)" />

    <header class="fixed top-0 z-20 mx-auto w-full border-b bg-card/70 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <AppLogo />

            <Button size="icon" class="md:hidden" variant="secondary" @click="() => (openMenu = true)"><Menu /></Button>

            <PostSearchInput v-if="['/', '/profile'].includes(url)" className="hidden md:block" />

            <div class="hidden items-center gap-4 md:flex">
                <AppearanceSelect />

                <ProfileBtn />
            </div>
        </div>
    </header>
</template>
