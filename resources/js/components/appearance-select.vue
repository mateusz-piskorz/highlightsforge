<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useAppearance } from '@/lib/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { updateAppearance } = useAppearance();
</script>

<template>
    <div class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800">
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon" class="size-7">
                    <Sun class="h-[1.2rem] w-[1.2rem] dark:hidden" />
                    <Moon class="hidden h-[1.2rem] w-[1.2rem] dark:block" />
                    <span class="sr-only">Toggle theme</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuItem @click="() => updateAppearance('light')">Light</DropdownMenuItem>
                <DropdownMenuItem @click="() => updateAppearance('dark')">Dark</DropdownMenuItem>
                <DropdownMenuItem @click="() => updateAppearance('system')">System</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
