<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, Settings, UserRound } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';
</script>

<template>
    <nav class="sticky top-[68px] hidden h-[calc(100vh-68px)] flex-col gap-4 border-r px-6 pt-12 md:flex lg:px-10">
        <Button class="justify-start py-5" variant="outline" as-child>
            <Link href="/"><ChevronLeft class="size-4" /> Back</Link>
        </Button>
        <Button class="justify-start py-5" variant="outline" as-child>
            <Link href="/profile"><UserRound class="size-4" /> Profile</Link>
        </Button>
        <Button class="justify-start py-5" variant="outline" as-child>
            <Link href="/settings"><Settings class="size-4" /> Settings</Link>
        </Button>
    </nav>
</template>
