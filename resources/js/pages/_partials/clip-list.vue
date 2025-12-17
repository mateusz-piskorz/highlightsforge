<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import UserAvatar from '@/components/user-avatar.vue';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import dayjs from 'dayjs';
import { MessageCircle, Sparkles } from 'lucide-vue-next';

const { data } = useQuery({
    queryKey: ['clips'],
    queryFn: async () => (await axios.get('api/clips')).data,
});
</script>

<template>
    <div class="space-y-20 px-0 sm:px-6 md:px-8 lg:px-0">
        <Card
            v-for="clip in data?.clips"
            :key="clip.id"
            class="mx-auto flex max-w-[889px] min-w-[310px] flex-col rounded-none pt-0 sm:rounded-xl lg:min-w-[660px]"
        >
            <video
                controls
                loop
                class="max-h-[500px] min-h-[200px] sm:rounded-t-xl lg:min-h-[370px] dark:opacity-90"
            >
                <source
                    :src="`/storage/${clip.file_path}`"
                    :type="clip.file_type"
                />
            </video>
            <div class="space-y-6 px-4">
                <time
                    class="text-sm text-muted-foreground 2xl:text-base"
                    title="Posted at"
                    :dateTime="dayjs(clip.createdAt).format('YYYY-MM-DD')"
                >
                    {{ dayjs(clip.createdAt).format('MMM D, YYYY') }}
                </time>

                <h1 class="text-xl">{{ clip.title }}</h1>

                <div
                    class="flex flex-wrap items-center justify-between gap-x-16 gap-y-6"
                >
                    <div class="flex items-center gap-4">
                        <UserAvatar
                            :name="clip.user.user_name"
                            :src="clip.user.avatar"
                        />

                        <div class="grid flex-1 text-left leading-tight">
                            <span class="truncate font-medium">
                                {{ clip.user.user_name }}
                            </span>
                            <span class="truncate text-sm text-muted-foreground"
                                >beginner</span
                            >
                        </div>
                    </div>

                    <div class="relative flex items-center gap-4">
                        <span class="flex items-center gap-2 text-lg">
                            <span class="sr-only">upvotes</span>
                            <Sparkles class="size-3.5" /> 2
                        </span>
                        <span class="flex items-center gap-2 text-lg">
                            <span class="sr-only">comments</span>
                            <MessageCircle class="size-3.5" />
                            17
                        </span>
                        <Button variant="link" class="pl-0 text-xl">
                            Report
                        </Button>
                    </div>
                </div>
            </div>
        </Card>
    </div>
</template>
