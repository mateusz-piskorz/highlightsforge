<script setup lang="ts">
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { computed } from 'vue';

const { onSubmit } = defineProps<{
    className?: string;
    onSubmit?: () => void;
}>();

const { setSorting, sorting } = usePostFilters();

const reactive = computed(() => sorting.value);
</script>

<template>
    <Select
        :model-value="reactive"
        v-on:update:model-value="
            (val) => {
                setSorting(val as any);
                onSubmit?.();
            }
        "
    >
        <SelectTrigger class="max-w-[150px]">
            <SelectValue />
        </SelectTrigger>
        <SelectContent>
            <SelectItem value="featured">Featured</SelectItem>
            <SelectItem value="new-posts">New Posts</SelectItem>
            <SelectItem value="latest-activity">Latest Activity</SelectItem>
        </SelectContent>
    </Select>
</template>
