<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/lib/composables/useInitials';
import { cn } from '@/lib/utils';

const { src, size, name } = defineProps<{
    src: string;
    name: string;
    size?: 'base' | 'lg';
}>();
</script>

<template>
    <Avatar
        :class="
            cn(
                'h-10 w-10 overflow-hidden rounded-full border',
                size === 'lg' && 'h-40 w-40',
            )
        "
    >
        <AvatarImage :src="src" alt="user avatar" />
        <AvatarFallback
            class="rounded-lg bg-sidebar-accent text-black dark:bg-neutral-700 dark:text-white"
        >
            {{ getInitials(name) }}
        </AvatarFallback>
    </Avatar>
</template>
