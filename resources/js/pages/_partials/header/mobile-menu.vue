<script setup lang="ts">
import AppearanceSelect from '@/components/appearance-select.vue';
import NewPostBtn from '@/components/new-post-btn.vue';
import PostSearchInput from '@/components/post-search-input.vue';
import Button from '@/components/ui/button/Button.vue';
import DialogClose from '@/components/ui/dialog/DialogClose.vue';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import SheetTitle from '@/components/ui/sheet/SheetTitle.vue';
import { useMobile } from '@/lib/composables/useMobile';
import { usePage } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';

const { open, setOpen } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const { user } = usePage().props.auth;

const isMobile = useMobile();

// const open = ref<null | 'register' | 'login' | 'post'>(null);
</script>

<template>
    <!-- <RegisterDialog :open="open === 'register'" :set-open="(val) => (open = val ? 'register' : null)" :login-action="() => (open = 'login')" /> -->
    <!-- <LoginDialog :open="open === 'login'" :set-open="(val) => (open = val ? 'login' : null)" :register-action="() => (open = 'register')" /> -->

    <Sheet :open="open" @update:open="setOpen" v-if="isMobile">
        <SheetContent side="left" class="px-5 py-5">
            <div class="mb-5 flex items-center justify-between">
                <SheetTitle class="text-2xl">Menu</SheetTitle>

                <DialogClose class="relative cursor-pointer">
                    <X class="size-5" />
                    <span class="sr-only">Close</span>
                </DialogClose>
            </div>
            <NewPostBtn @click="() => console.log('dwa')" />

            <PostSearchInput :onSubmit="() => setOpen(false)" />

            <div class="flex items-center gap-4">
                <AppearanceSelect />

                <Button variant="secondary">{{ user ? 'Profile' : 'Login' }}</Button>
            </div>
        </SheetContent>
    </Sheet>
</template>
