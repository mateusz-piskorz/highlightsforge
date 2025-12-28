<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed, ref } from 'vue';

const { className, content } = defineProps<{
    content: string;
    className?: string;
}>();

const maxLength = 225;
const isExpanded = ref(false);

const isTooLong = computed(() => content.length > maxLength);
</script>

<template>
    <p :class="cn('font-sm', className)">
        {{ !isTooLong || isExpanded ? content : `${content.substring(0, maxLength)}...` }}
        <Button v-if="isTooLong && !isExpanded" class="pl-1 text-muted-foreground" @click="isExpanded = true" variant="link"> more </Button>
    </p>
</template>
