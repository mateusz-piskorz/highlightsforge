<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronLeft, Settings, UserRound } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';

const { className } = defineProps<{ className?: string }>();

const page = usePage();
const { url } = page;
const { user } = page.props.auth;
</script>

<template>
    <nav :class="cn('flex flex-col gap-4', className)">
        <Button class="justify-start py-5" variant="outline" as-child>
            <Link href="/"><ChevronLeft class="size-4" />Back</Link>
        </Button>
        <Button class="justify-start py-5" :variant="url === '/profile' ? 'secondary' : 'outline'" as-child>
            <Link href="/profile"><UserRound class="size-4" />Profile</Link>
        </Button>
        <Button class="justify-start py-5" :variant="url === '/settings' ? 'secondary' : 'outline'" as-child>
            <Link href="/settings"><Settings class="size-4" />Settings</Link>
        </Button>
        <Button v-if="user?.role === 'admin'" class="justify-start py-5" :variant="url === '/reported-posts' ? 'secondary' : 'outline'" as-child>
            <Link href="/reports"><Settings class="size-4" />Reports</Link>
        </Button>
    </nav>
</template>
