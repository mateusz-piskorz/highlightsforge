<script setup lang="ts">
import ConfirmDialog from '@/components/confirm-dialog.vue';
import InputField from '@/components/fields/input-field/input-field.vue';
import Heading from '@/components/heading.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import Label from '@/components/ui/label/Label.vue';
import { setFormApiErrors } from '@/lib/set-form-api-errors';
import { router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Edit } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import EmailVerifyDialog from './email-verify-dialog.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const open = ref(false);
const openConfirm = ref(false);

const form = useForm({ user_name: user.value.user_name });

const onSubmit = async () => {
    const { data } = await axios.post('/api/update-profile', form.data());

    if (!data.success) {
        toast.error(data.message);
        setFormApiErrors({ form, data });
        return;
    }

    toast.success(data.message);
    form.defaults(form.data());
};
</script>

<template>
    <ConfirmDialog
        :open="openConfirm"
        :set-open="(val) => (openConfirm = val)"
        @continueEvent="
            async () => {
                const { data } = await axios.delete('/api/remove-email');

                if (!data.success) {
                    toast.error(data.message);
                    return;
                }

                toast.success(data.message);
                openConfirm = false;
                router.reload();
            }
        "
        btn-text="Remove E-mail"
        description="Do you really want to remove your email? This is a permanent operation and can't be undone."
        title="Remove E-mail from to this account"
    />

    <EmailVerifyDialog :open="open" :set-open="(val) => (open = val)" />

    <div class="flex flex-col gap-6 px-5 sm:px-6 md:px-8 lg:px-10">
        <Heading
            title="Profile Information"
            description="Update your profile username"
        />

        <form @submit.prevent="onSubmit" class="space-y-10">
            <InputField
                v-model="form.user_name"
                :error-message="form.errors.user_name"
                autocomplete="off"
                label="User Name"
            />

            <div class="space-y-4">
                <Label>E-mail</Label>
                <p
                    v-if="user.email"
                    class="inline-flex items-center gap-2 text-muted-foreground"
                >
                    {{ user.email }} -
                    <span class="text-green-500">verified</span>
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative"
                            >
                                <span class="sr-only">Edit E-mail</span>
                                <Edit />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem @click="() => (open = true)"
                                >Edit</DropdownMenuItem
                            >
                            <DropdownMenuItem
                                @click="() => (openConfirm = true)"
                                >Remove</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                </p>

                <Button
                    type="button"
                    v-if="!user.email"
                    @click="() => (open = true)"
                    variant="secondary"
                    >Verify E-mail</Button
                >
            </div>

            <Button :disabled="!form.isDirty">Save</Button>
        </form>
    </div>
</template>
