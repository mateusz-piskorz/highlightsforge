<script setup lang="ts">
import { Button } from './ui/button';

const { btnText, onDelete } = defineProps<{
    btnText: string;
    onDelete: () => void;
}>();
</script>

<template>
    <div class="w-full space-y-2 rounded-lg border border-red-200 bg-red-100 p-4 dark:border-red-200/10 dark:bg-red-700/10">
        <h2 class="text-lg font-bold">Warning</h2>
        <p class="text-sm">Please proceed with caution, this action cannot be undone.</p>
        <Button class="mt-4" variant="destructive" @click="onDelete">
            {{ btnText }}
        </Button>
    </div>
</template>
