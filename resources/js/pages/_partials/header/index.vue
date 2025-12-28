<script setup lang="ts">
import AppLogo from '@/components/app-logo.vue';
import AppearanceSelect from '@/components/appearance-select.vue';
import LoginDialog from '@/components/login-dialog.vue';
import PostSearchInput from '@/components/post-search-input.vue';
import RegisterDialog from '@/components/register-dialog.vue';
import Button from '@/components/ui/button/Button.vue';
import { usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';
import { ref } from 'vue';
import MobileMenu from './mobile-menu.vue';

const { user } = usePage().props.auth;

const open = ref<null | 'register' | 'login' | 'post'>(null);
const openMenu = ref(false);
</script>

<template>
    <RegisterDialog :open="open === 'register'" :set-open="(val) => (open = val ? 'register' : null)" :login-action="() => (open = 'login')" />
    <LoginDialog :open="open === 'login'" :set-open="(val) => (open = val ? 'login' : null)" :register-action="() => (open = 'register')" />
    <MobileMenu :open="openMenu" :set-open="(val) => (openMenu = val)" />

    <header class="fixed top-0 z-20 mx-auto w-full border-b bg-card/70 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <AppLogo />

            <Button size="icon" class="md:hidden" variant="secondary" @click="() => (openMenu = true)"><Menu /></Button>

            <PostSearchInput className="hidden md:block" />

            <div class="hidden items-center gap-4 md:flex">
                <AppearanceSelect />

                <Button
                    variant="secondary"
                    @click="
                        () => {
                            if (!user) {
                                open = 'login';
                            }
                        }
                    "
                >
                    {{ user ? 'Profile' : 'Login' }}
                </Button>
            </div>
        </div>
    </header>
</template>
