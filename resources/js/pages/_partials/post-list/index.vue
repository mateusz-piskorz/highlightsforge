<script setup lang="ts">
import CreatePostDialog from '@/components/create-post-dialog.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserAvatar from '@/components/user-avatar.vue';
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { usePage } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { Settings } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import PostActions from './post-actions.vue';

defineEmits(['commentsEvent']);
const { user } = usePage().props.auth;

const { q, sorting } = usePostFilters();

const { data, refetch } = useQuery({
    queryKey: ['posts', q, sorting],
    queryFn: async () => (await axios.get('api/posts', { params: { q: q.value, sorting: sorting.value } })).data,
});

const open = ref<null | 'register' | 'login' | 'post'>(null);

const upvoteDisabled = ref(false);
</script>

<template>
    <CreatePostDialog :open="open === 'post'" :set-open="(val) => (open = val ? 'post' : null)" />
    <div class="mx-auto max-w-[900px]">
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

                <PostActions
                    @commentsClick="$emit('commentsEvent', { id: post.id, totalResponses: post.comments_count })"
                    :comments_count="post.comments_count"
                    :isOwner="post.user.id === user?.id"
                    :upvoted="post.upvoted"
                    :upvotes_count="post.upvotes_count"
                    :upvoteDisabled
                    @upvoteEvent="
                        async () => {
                            upvoteDisabled = true;
                            const { data } = await axios.post(`/api/posts/${post.id}/upvote`);

                            if (!data.success) {
                                toast.error(data.message);
                                return;
                            }

                            await refetch();
                            toast.success(data.message);
                            upvoteDisabled = false;
                        }
                    "
                />
            </Card>
        </div>
    </div>
</template>
