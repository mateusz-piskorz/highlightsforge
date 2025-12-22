<script setup lang="ts">
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { cn } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { computed } from 'vue';
import InputField from './fields/input-field/input-field.vue';
import Button from './ui/button/Button.vue';

const { className, onSubmit } = defineProps<{
    className?: string;
    onSubmit?: () => void;
}>();

const { setQ, q } = usePostFilters();
const reactive = computed(() => q.value);
const form = useForm({ phrase: reactive.value });
</script>

<template>
    <form
        :class="cn('space-y-6', className)"
        @submit.prevent="
            () => {
                setQ(form.phrase);
                onSubmit?.();
            }
        "
    >
        <div class="flex">
            <InputField v-model="form.phrase" :error-message="form.errors.phrase" class="rounded-l-md dark:bg-input" placeholder="search" />
            <Button variant="ghost" class="rounded-l-none dark:bg-input">
                <Search class="size-4" />
            </Button>
        </div>
    </form>
</template>
