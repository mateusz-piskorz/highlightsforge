<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Sparkles } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { active, disabled, upvotes } = defineProps<{
    disabled: boolean;
    active: boolean;
    upvotes: number;
}>();
</script>

<template>
    <Button variant="ghost" :disabled="disabled">
        <span class="sr-only">upvotes</span>
        <Sparkles :fill="active ? 'var(--accent)' : 'none'" :class="cn(active && 'text-accent')" />
        {{ upvotes }}
    </Button>
</template>
