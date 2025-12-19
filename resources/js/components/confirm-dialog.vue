<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from './ui/button';

const { open, setOpen, btnText, description, title } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
    title: string;
    description: string;
    btnText: string;
}>();
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent class="md:min-w-auto">
            <DialogTitle>{{ title }}</DialogTitle>
            <DialogDescription>{{ description }}</DialogDescription>
            <div class="flex gap-4">
                <Button
                    @click="() => setOpen(false)"
                    type="button"
                    variant="secondary"
                    >Cancel</Button
                >
                <Button @click="$emit('continueEvent')" variant="destructive">{{
                    btnText
                }}</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
