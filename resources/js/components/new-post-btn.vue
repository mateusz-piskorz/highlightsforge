<script setup lang="ts">
import { cn } from '@/lib/utils';
import { PlusCircle } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { className } = defineProps<{
    className?: string;
}>();
</script>

<template>
    <Button :class="cn('mx-0', className)" variant="accent">
        <PlusCircle />
        <span>upload new post</span></Button
    >
</template>
