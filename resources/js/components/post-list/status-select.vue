<script setup lang="ts">
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import axios from 'axios';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const { status, refetch, id } = defineProps<{ status: 'pending' | 'draft' | 'published'; refetch: () => void; id: number }>();

const loading = ref(false);
</script>

<template>
    <Select
        :disabled="status === 'pending'"
        :model-value="status"
        v-on:update:model-value="
            async (val) => {
                loading = true;
                const { data } = await axios.post(`/api/posts/${id}/status`, { status: val });

                if (!data.success) {
                    toast.error(data.message);
                    loading = false;
                    return;
                }

                refetch();
                toast.success(data.message);
                loading = false;
            }
        "
    >
        <SelectTrigger class="max-w-[150px]">
            <SelectValue />
        </SelectTrigger>
        <SelectContent>
            <SelectItem disabled value="pending">Pending</SelectItem>
            <SelectItem value="draft">Draft</SelectItem>
            <SelectItem value="published">Published</SelectItem>
        </SelectContent>
    </Select>
</template>
