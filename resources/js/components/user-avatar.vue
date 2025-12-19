<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/lib/composables/useInitials';
import { cn } from '@/lib/utils';
import dayjs from 'dayjs';

const { src, size, name, youIndicator, commentedAt } = defineProps<{
    src: string | null;
    name: string;
    size?: 'base' | 'lg';
    youIndicator?: boolean;
    commentedAt?: Date | string;
}>();
</script>

<template>
    <div class="flex items-center gap-2">
        <Avatar
            :class="
                cn(
                    'h-9 w-9 overflow-hidden rounded-full border',
                    size === 'lg' && 'h-40 w-40',
                )
            "
        >
            <AvatarImage :src="src || ''" alt="user avatar" />
            <AvatarFallback
                class="rounded-lg bg-sidebar-accent text-black dark:bg-neutral-700 dark:text-white"
            >
                {{ getInitials(name) }}
            </AvatarFallback>
        </Avatar>

        <div class="grid flex-1 text-sm leading-tight">
            <span class="truncate font-medium">
                {{ name }}
                <span v-if="youIndicator" class="text-muted-foreground">
                    (you)
                </span>
            </span>

            <time
                v-if="commentedAt"
                class="text-xs text-muted-foreground"
                title="Commented at"
                :dateTime="dayjs(commentedAt).format('YYYY-MM-DD')"
            >
                {{ dayjs(commentedAt).format('MMMM D, YYYY') }}
            </time>
        </div>
    </div>
</template>
