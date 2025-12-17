<script setup lang="ts">
import AppLogo from '@/components/app-logo.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import Separator from '@/components/ui/separator/Separator.vue';
import UserAvatar from '@/components/user-avatar.vue';
import { usePage } from '@inertiajs/vue3';
import { PlusCircle, Search } from 'lucide-vue-next';
import { ref } from 'vue';
import CreateClipDialog from './create-clip-dialog.vue';
import LoginDialog from './login-dialog.vue';
import RegisterDialog from './register-dialog.vue';

const { user } = usePage().props.auth;

const open = ref<null | 'register' | 'login' | 'clip'>(null);
</script>

<template>
    <RegisterDialog
        :open="open === 'register'"
        :set-open="(val) => (open = val ? 'register' : null)"
        :login-action="() => (open = 'login')"
    />
    <LoginDialog
        :open="open === 'login'"
        :set-open="(val) => (open = val ? 'login' : null)"
        :register-action="() => (open = 'register')"
    />
    <CreateClipDialog
        :open="open === 'clip'"
        :set-open="(val) => (open = val ? 'clip' : null)"
    />

    <div
        class="mx-auto space-y-4 space-x-4 lg:sticky lg:top-20 lg:flex lg:flex-col"
    >
        <AppLogo />

        <!-- this needs to be a form -->
        <div class="relative">
            <Input placeholder="search" />
            <Button
                variant="link"
                class="absolute top-1/2 right-[15px] -translate-y-1/2 p-0"
            >
                <Search class="size-4" />
            </Button>
        </div>

        <Select default-value="featured">
            <SelectTrigger class="py-1.5">
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="featured"> Featured </SelectItem>
                <SelectItem value="new-clips"> new-clips </SelectItem>
                <SelectItem value="latest-activity">
                    latest-activity
                </SelectItem>
            </SelectContent>
        </Select>

        <span>1437 Results</span>

        <Separator />

        <Button
            class="mx-0"
            variant="accent"
            @click="() => (open = user ? 'clip' : 'login')"
        >
            <PlusCircle />
            <span>upload new clip</span></Button
        >

        <div v-if="user" class="flex items-center gap-4">
            <UserAvatar :name="user.user_name" :src="user.avatar" />

            <div class="grid flex-1 text-left leading-tight">
                <span class="truncate font-medium">
                    {{ user.user_name }}
                </span>
                <span class="truncate text-sm text-muted-foreground"
                    >beginner</span
                >
            </div>
        </div>

        <Button v-if="!user" variant="secondary" @click="() => (open = 'login')"
            >Login</Button
        >
    </div>
</template>
