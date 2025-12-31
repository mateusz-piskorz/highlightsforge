<script setup lang="ts">
import AppearanceSelect from '@/components/appearance-select.vue';
import ProfileBtn from '@/components/profile-btn.vue';
import DialogClose from '@/components/ui/dialog/DialogClose.vue';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import SheetTitle from '@/components/ui/sheet/SheetTitle.vue';
import { useMobile } from '@/lib/composables/useMobile';
import { usePage } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import SettingsSidebar from '../settings-sidebar.vue';

const { open, setOpen } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
}>();

const isMobile = useMobile();

const { url } = usePage();
</script>

<template>
    <Sheet :open="open" @update:open="setOpen" v-if="isMobile">
        <SheetContent side="left" class="px-5 py-5">
            <div class="mb-5 flex items-center justify-between">
                <SheetTitle class="text-2xl">Menu</SheetTitle>

                <DialogClose class="relative cursor-pointer">
                    <X class="size-5" />
                    <span class="sr-only">Close</span>
                </DialogClose>
            </div>

            <div class="flex flex-col items-start justify-start gap-4">
                <SettingsSidebar v-if="['/profile', '/settings'].includes(url)" />
                <ProfileBtn v-if="url === '/'" />
                <AppearanceSelect :fullLabel="true" />
            </div>
        </SheetContent>
    </Sheet>
</template>
