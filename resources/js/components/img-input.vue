<!-- components/ui/InputField.vue -->
<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Upload } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { z } from 'zod';
import { Button } from './ui/button';
import Card from './ui/card/Card.vue';

const { btnState, onChange } = defineProps<{
    btnState?: boolean;
    onChange: (arg: File) => void;
}>();

const ACCEPTED_IMAGE_EXT = ['svg', 'jpeg', 'jpg', 'png', 'webp'];
const ACCEPTED_IMAGE_TYPES = [
    'image/svg+xml',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/webp',
];

const imgSchema = z.object({
    img: z
        .instanceof(File)
        .refine((file: File) => file.size < 15000000, 'max file size is 15mb')
        .refine(
            (file: File) => ACCEPTED_IMAGE_TYPES.includes(file.type),
            `Allowed file extensions: ${ACCEPTED_IMAGE_EXT.join(', ')}`,
        )
        .nullable(),
});
</script>
<template>
    <Card
        :class="
            cn(
                'border-2 border-dashed p-0',
                !Boolean(btnState) && 'h-[280px] w-[350px]',
                Boolean(btnState) &&
                    'flex-row items-start border-0 bg-transparent dark:bg-transparent',
            )
        "
        variant="outline-light-theme"
    >
        <div :class="cn(btnState ? 'space-x-4 text-center' : 'h-full')">
            <label
                :class="
                    cn(
                        'relative mx-auto',
                        !btnState &&
                            'flex h-full w-full flex-col items-center justify-center',
                    )
                "
            >
                <Button v-if="btnState" variant="outline"
                    >Choose different</Button
                >
                <template v-else>
                    <Upload className="size-11" />
                    <p className="font-medium">Choose File</p>
                    <p className="text-muted-foreground font-normal">
                        {{ ACCEPTED_IMAGE_EXT.join(', ') }}
                    </p>
                </template>

                <input
                    :class="
                        cn(
                            'absolute top-0 left-0 h-full w-full cursor-pointer opacity-0',
                        )
                    "
                    type="file"
                    accept="image/*"
                    @change="
                        async (event: any) => {
                            const file =
                                event.target.files && event.target.files[0];
                            const validated = imgSchema.safeParse({
                                img: file,
                            });
                            if (validated.success) {
                                onChange(file);
                            } else {
                                toast.error(
                                    validated.error.errors[0]?.message ||
                                        'Invalid image file',
                                );
                            }
                        }
                    "
                />
            </label>
        </div>
    </Card>
</template>
