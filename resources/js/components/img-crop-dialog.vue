<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { ref } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import Button from './ui/button/Button.vue';

const { open, setOpen, onCrop } = defineProps<{
    open: boolean;
    setOpen: (arg: boolean) => void;
    onCrop: (img: string) => void;
    imgSrc: string;
}>();

const lastChangeData = ref(null);

const handleChange = ({ coordinates, canvas }) => {
    lastChangeData.value = { coordinates, canvas };
};

const handleCropButtonClick = () => {
    if (lastChangeData.value && lastChangeData.value.canvas) {
        const { canvas } = lastChangeData.value;
        canvas.toBlob((blob) => {
            onCrop(blob);
        }, 'image/jpeg');
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="setOpen">
        <DialogContent class="flex flex-col">
            <DialogTitle>crop image</DialogTitle>
            <DialogDescription>crop img description</DialogDescription>
            <div class="w-full">
                <div class="flex w-full flex-col items-center gap-4">
                    <Cropper
                        class="w-full"
                        :src="imgSrc"
                        :stencil-size="{
                            width: 1000,
                            height: 1000,
                        }"
                        :canvas="{
                            minHeight: 0,
                            minWidth: 0,
                            maxHeight: 512,
                            maxWidth: 512,
                        }"
                        @change="handleChange"
                    />

                    <div class="flex gap-4">
                        <Button @click="() => setOpen(false)" type="button" variant="secondary">Cancel</Button>
                        <Button :disabled="!lastChangeData" @click="handleCropButtonClick">Crop Image</Button>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
