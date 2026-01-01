<script setup lang="ts">
import CollapsibleText from '@/components/collapsible-text.vue';
import PostDialog from '@/components/post-dialog.vue';
import SpinLoader from '@/components/spin-loader.vue';
import Card from '@/components/ui/card/Card.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserAvatar from '@/components/user-avatar.vue';
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { usePage } from '@inertiajs/vue3';
import { useInfiniteQuery } from '@tanstack/vue-query';
import { useIntersectionObserver } from '@vueuse/core';
import axios from 'axios';
import { Settings } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import PostActions from './post-actions.vue';

defineEmits(['commentsEvent']);
const { user } = usePage().props.auth;

const { q, sorting } = usePostFilters();

const { data, refetch, fetchNextPage, hasNextPage, isFetchingNextPage, isPending } = useInfiniteQuery({
    queryKey: ['posts', q, sorting],
    queryFn: async ({ pageParam }) => (await axios.get('api/posts', { params: { q: q.value, sorting: sorting.value, page: pageParam } })).data,
    getNextPageParam: (lastPage) => (lastPage.next_page_url ? lastPage.current_page + 1 : undefined),
    initialPageParam: 1,
});

const open = ref<boolean>(false);

const results = computed(() => data.value?.pages.flatMap((page) => page.data) ?? []);

const upvoteDisabled = ref(false);

const loadMoreTrigger = ref<HTMLElement | null>(null);

const selected = ref<null | { title: string; description?: string; id: number }>(null);

useIntersectionObserver(loadMoreTrigger, ([{ isIntersecting }]) => {
    if (isIntersecting && hasNextPage.value && !isFetchingNextPage.value) fetchNextPage();
});
</script>

<template>
    <PostDialog :open="open" :set-open="(val) => (open = val)" :post="selected" />

    <div class="mx-auto max-w-[900px]">
        <div class="space-y-20 px-0 sm:px-6 md:px-8 lg:px-0">
            <Card v-for="post in results" :key="post.id" class="mx-auto flex min-w-[310px] flex-col rounded-none sm:rounded-xl lg:min-w-[660px]">
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
                            <DropdownMenuItem
                                v-if="post.user_id === user?.id"
                                @click="
                                    () => {
                                        selected = { id: post.id, title: post.title, description: post.description };
                                        open = true;
                                    }
                                "
                                >Update</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <h1 class="line-clamp-2 px-5 font-mono text-lg font-medium">{{ post.title }}</h1>
                <CollapsibleText v-if="post.description" :content="post.description" class="px-5 text-muted-foreground" />

                <img
                    v-if="post.file_type.split('/')[0] === 'image'"
                    :src="`/storage/${post.file_path}`"
                    class="max-h-[510px] min-h-[200px] lg:min-h-[370px] dark:opacity-90"
                />
                <video
                    v-if="post.file_type.split('/')[0] === 'video'"
                    controls
                    loop
                    class="max-h-[510px] min-h-[200px] lg:min-h-[370px] dark:opacity-90"
                >
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
            <div ref="loadMoreTrigger" class="flex h-10 items-center justify-center">
                <SpinLoader v-if="isFetchingNextPage || isPending" />
            </div>
        </div>
    </div>
</template>
