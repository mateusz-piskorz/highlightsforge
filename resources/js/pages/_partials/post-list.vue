<script setup lang="ts">
import CreatePostDialog from '@/components/create-post-dialog.vue';
import NewPostBtn from '@/components/new-post-btn.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import UserAvatar from '@/components/user-avatar.vue';
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { usePage } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { MessageCircle, Settings, Sparkles } from 'lucide-vue-next';
import { ref } from 'vue';

defineEmits(['commentsEvent']);

const { q } = usePostFilters();

const { data } = useQuery({
    queryKey: ['posts', q],
    queryFn: async () => (await axios.get('api/posts', { params: { q: q.value } })).data,
});

const { user } = usePage().props.auth;

const open = ref<null | 'register' | 'login' | 'post'>(null);
</script>

<template>
    <CreatePostDialog :open="open === 'post'" :set-open="(val) => (open = val ? 'post' : null)" />
    <div class="mx-auto max-w-[900px]">
        <div class="my-10 space-y-5 px-5 lg:px-0">
            <NewPostBtn @click="() => (open = user ? 'post' : 'login')" />

            <Select default-value="featured">
                <SelectTrigger class="max-w-[150px]">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="featured"> Featured </SelectItem>
                    <SelectItem value="new-posts"> new-posts </SelectItem>
                    <SelectItem value="latest-activity"> latest-activity </SelectItem>
                </SelectContent>
            </Select>
        </div>
        <div class="space-y-20 px-0 sm:px-6 md:px-8 lg:px-0">
            <Card v-for="post in data?.posts" :key="post.id" class="mx-auto flex min-w-[310px] flex-col rounded-none sm:rounded-xl lg:min-w-[660px]">
                <div class="flex items-center justify-between px-4">
                    <UserAvatar :name="post.user.user_name" :src="post.user.avatar" :commentedAt="post.created_at" />
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button variant="ghost" size="icon" class="size-7">
                                <Settings class="h-[1.2rem] w-[1.2rem]" />
                                <span class="sr-only">Settings</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem @click="() => console.log('report')">Report</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
                <h1 class="line-clamp-2 px-5 font-mono text-xl font-medium">{{ post.title }}</h1>

                <video controls loop class="max-h-[510px] min-h-[200px] lg:min-h-[370px] dark:opacity-90">
                    <source :src="`/storage/${post.file_path}`" :type="post.file_type" />
                </video>

                <div class="flex items-center justify-between gap-20 px-4">
                    <div class="flex items-center">
                        <Button variant="ghost" class="relative flex items-center gap-2 text-lg">
                            <span class="sr-only">upvotes</span>
                            <Sparkles class="size-3.5" /> 2
                        </Button>

                        <Button
                            @click="$emit('commentsEvent', { id: post.id, totalResponses: post.comments_count })"
                            variant="ghost"
                            class="relative flex items-center gap-2 text-lg"
                        >
                            <span class="sr-only">comments</span>
                            <MessageCircle class="size-4.5" />
                            {{ post.comments_count }}
                        </Button>
                    </div>
                </div>
            </Card>
        </div>
    </div>
</template>
