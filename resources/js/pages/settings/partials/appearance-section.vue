<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/heading.vue';
</script>

<template>
    <div class="flex flex-col items-start space-y-6 sm:px-6 md:px-8 lg:px-10">
        <Heading
            title="Appearance settings"
            description="Update your account's appearance settings"
        />

        <AppearanceTabs />
    </div>
</template>
