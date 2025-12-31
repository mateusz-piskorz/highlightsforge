<script setup lang="ts">
import { useCommonDialogs } from '@/lib/composables/useCommonDialogs';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { PlusCircle } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { className, onClick } = defineProps<{
    className?: string;
    onClick?: () => void;
}>();

const { user } = usePage().props.auth;
const { setOpen } = useCommonDialogs();
</script>

<template>
    <Button
        :class="cn('mx-0', className)"
        variant="accent"
        @click="
            () => {
                setOpen(user ? 'post' : 'login');
                onClick?.();
            }
        "
    >
        <PlusCircle />
        <span>upload new post</span></Button
    >
</template>
