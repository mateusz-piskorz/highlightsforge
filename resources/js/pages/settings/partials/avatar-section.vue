<script setup lang="ts">
import ConfirmDialog from '@/components/confirm-dialog.vue';
import Heading from '@/components/heading.vue';
import ImgCropDialog from '@/components/img-crop-dialog.vue';
import ImgInput from '@/components/img-input.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import UserAvatar from '@/components/user-avatar.vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage();
const user = computed(() => page.props.auth.user);

const openConfirm = ref(false);
const openCrop = ref(false);
const imSrc = ref<null | string>(null);

const changeHandler = async (file: File) => {
    const url = URL.createObjectURL(file);
    imSrc.value = url;
    openCrop.value = true;
};

const onCrop = async (avatar: string) => {
    const formData = new FormData();
    formData.append('avatar', avatar);
    const { data } = await axios.post('api/upload-avatar', formData);

    if (!data.success) {
        toast.error(data.message);
        return;
    }

    router.reload();
    toast.success(data.message);
    openCrop.value = false;
    imSrc.value = null;
};

const deleteAvatar = async () => {
    const { data } = await axios.delete('/api/remove-avatar');
    if (!data.success) {
        toast.error(data.message);
        return;
    }
    toast.success(data.message);
    router.reload();
    openConfirm.value = false;
};
</script>

<template>
    <ImgCropDialog v-if="imSrc" :open="openCrop" :set-open="(val) => (openCrop = val)" :onCrop="onCrop" :img-src="imSrc" />

    <ConfirmDialog
        :open="openConfirm"
        :set-open="(val) => (openConfirm = val)"
        @continueEvent="deleteAvatar"
        btn-text="Remove Avatar"
        title="Remove user Avatar"
        description="Do you really want to remove your Avatar? This is a permanent operation and can't be undone."
    />

    <div class="flex flex-col space-y-6 px-5 sm:px-6 md:px-8 lg:px-10">
        <Heading title="Update avatar" description="Choose a clear, distinctive image to help others recognize you." />

        <Card v-if="user.avatar" class="flex max-w-[400px] items-center justify-center gap-8 text-center">
            <UserAvatar :name="user.user_name" size="lg" :src="user.avatar" />
        </Card>
        <div class="flex gap-4">
            <Button v-if="user.avatar" variant="destructive" @click="openConfirm = true">Remove</Button>
            <ImgInput :onChange="changeHandler" :btn-state="Boolean(user.avatar)" />
        </div>
    </div>
</template>
