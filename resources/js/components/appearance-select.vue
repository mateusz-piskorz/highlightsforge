<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useAppearance } from '@/lib/composables/useAppearance';
import { cn } from '@/lib/utils';
import { Moon, Sun } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { fullLabel } = defineProps<{ fullLabel?: boolean }>();
const { updateAppearance, appearance } = useAppearance();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger asChild>
            <Button :variant="fullLabel ? 'outline' : 'ghost'" size="default" :class="cn('min-w-5 gap-4')">
                <span v-if="fullLabel" class="text-sm first-letter:uppercase">{{ appearance }}</span>
                <Sun class="size-4 dark:hidden" />
                <Moon class="hidden size-4 dark:block" />
                <span class="sr-only">Toggle theme</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="() => updateAppearance('light')">Light</DropdownMenuItem>
            <DropdownMenuItem @click="() => updateAppearance('dark')">Dark</DropdownMenuItem>
            <DropdownMenuItem @click="() => updateAppearance('system')">System</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
