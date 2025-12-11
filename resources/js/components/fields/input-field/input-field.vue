<!-- components/ui/InputField.vue -->
<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { computed, useAttrs } from 'vue';

const props = defineProps<{
    modelValue?: string | number;
    errorMessage?: string;
    label?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const attrs = useAttrs();

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});

const inputId = computed(() => {
    return (
        (attrs.id as string) ||
        `input-field-${Math.random().toString(36).substr(2, 9)}`
    );
});
</script>

<template>
    <div class="space-y-4">
        <Label v-if="props.label" :for="inputId">{{ props.label }}</Label>

        <Input
            v-model="value"
            v-bind="attrs"
            :id="inputId"
            :aria-describedby="errorMessage ? `${inputId}-error` : undefined"
        />

        <InputError
            v-if="errorMessage"
            :message="errorMessage"
            class="mt-2"
            :id="`${inputId}-error`"
        />
    </div>
</template>
