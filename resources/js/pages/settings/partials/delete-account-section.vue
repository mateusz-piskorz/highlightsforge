<script setup lang="ts">
import ConfirmDialog from '@/components/confirm-dialog.vue';
import DangerActionCard from '@/components/danger-action-card.vue';
import Heading from '@/components/heading.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const openConfirm = ref(false);

const deleteHandler = async () => {
    const { data } = await axios.delete('/api/remove-account');
    if (!data.success) {
        toast.error(data.message);
        return;
    }
    toast.success(data.message);
    router.visit('/');
};
</script>

<template>
    <ConfirmDialog
        :open="openConfirm"
        :set-open="(val) => (openConfirm = val)"
        :onContinue="deleteHandler"
        btn-text="Remove E-mail"
        title="Remove user account"
        description="Do you really want to remove your Account? This is a permanent operation and can't be undone. All your resources will be lost"
    />

    <div
        class="flex flex-col items-start space-y-6 px-5 sm:px-6 md:px-8 lg:px-10"
    >
        <Heading
            title="Delete account"
            description="delete account and all of its resources permanently"
        />

        <DangerActionCard
            btn-text="Delete Account"
            :onDelete="() => (openConfirm = true)"
        />
    </div>
</template>
