<script setup lang="ts">
import CollapsibleText from '@/components/collapsible-text.vue';
import ConfirmDialog from '@/components/confirm-dialog.vue';
import PostDialog from '@/components/post-dialog.vue';
import SpinLoader from '@/components/spin-loader.vue';
import Card from '@/components/ui/card/Card.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserAvatar from '@/components/user-avatar.vue';
import { useCommonDialogs } from '@/lib/composables/useCommonDialogs';
import { usePostFilters } from '@/lib/composables/usePostFilters';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { useInfiniteQuery } from '@tanstack/vue-query';
import { useIntersectionObserver } from '@vueuse/core';
import axios from 'axios';
import { Settings } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import Button from '../ui/button/Button.vue';
import PostActions from './post-actions.vue';
import StatusSelect from './status-select.vue';

const { authorId, className, mode } = defineProps<{ authorId?: number; className?: string; mode?: 'edit' | 'report' }>();

const page = usePage();
const { user } = page.props.auth;

const { q, sorting } = usePostFilters();

const { data, refetch, fetchNextPage, hasNextPage, isFetchingNextPage, isPending } = useInfiniteQuery({
    queryKey: ['posts', q, sorting],
    queryFn: async ({ pageParam }) => {
        return (
            await axios.get('api/posts', {
                params: {
                    authorId,
                    q: q.value,
                    sorting: sorting.value,
                    page: pageParam,
                    status: mode === 'report' ? 'reported' : mode === 'edit' ? null : 'published',
                },
            })
        ).data;
    },
    getNextPageParam: (lastPage) => (lastPage.next_page_url ? lastPage.current_page + 1 : undefined),
    initialPageParam: 1,
});

const { setSelectedPost } = useCommonDialogs();

const open = ref<false | 'delete' | 'upsert'>(false);

const results = computed(() => data.value?.pages.flatMap((page) => page.data) ?? []);

const upvoteDisabled = ref(false);
const reportDisabled = ref(false);

const loadMoreTrigger = ref<HTMLElement | null>(null);

const selected = ref<null | { title: string; description?: string; id: number }>(null);

useIntersectionObserver(loadMoreTrigger, ([{ isIntersecting }]) => {
    if (isIntersecting && hasNextPage.value && !isFetchingNextPage.value) fetchNextPage();
});

const adminReviewHandler = async ({ postId, review }: { postId: number; review: 'approve' | 'ban' }) => {
    const { data } = await axios.post(`/api/posts/${postId}/admin-review`, { review });

    if (!data.success) {
        toast.error(data.message);
        return;
    }

    await refetch();
    toast.success(data.message);
};
</script>

<template>
    <ConfirmDialog
        :open="open === 'delete'"
        :setOpen="(val) => (open = val ? 'delete' : false)"
        title="Remove post"
        description="Do you really want to remove your Post? This is a permanent operation and can't be undone."
        btnText="Remove"
        @continueEvent="
            async () => {
                if (!selected) return;

                const { data } = await axios.delete(`/api/posts/${selected.id}`);

                if (!data.success) {
                    console.log('here123');
                    toast.error(data.message);
                    return;
                }

                await refetch();
                toast.success(data.message);
                open = false;
            }
        "
    />
    <PostDialog :open="open === 'upsert'" :set-open="(val) => (open = val ? 'upsert' : false)" :post="selected" />

    <div :class="cn('max-w-[900px]', className)">
        <div class="space-y-20">
            <Card v-for="post in results" :key="post.id" class="mx-auto flex min-w-[310px] flex-col rounded-none sm:rounded-xl lg:min-w-[660px]">
                <div class="flex items-center justify-between px-4">
                    <UserAvatar :name="post.user.user_name" :src="post.user.avatar" :commentedAt="post.created_at" />
                    <div class="flex items-center gap-4">
                        <StatusSelect v-if="mode === 'edit'" :status="post.status" :refetch="refetch" :id="post.id" />
                        <Button
                            v-if="mode === 'report'"
                            @click="() => adminReviewHandler({ postId: post.id, review: 'approve' })"
                            size="sm"
                            variant="secondary"
                            >Approve</Button
                        >
                        <Button
                            v-if="mode === 'report'"
                            @click="() => adminReviewHandler({ postId: post.id, review: 'ban' })"
                            size="sm"
                            variant="destructive"
                            >Ban</Button
                        >
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" size="icon" class="size-7">
                                    <Settings class="h-[1.2rem] w-[1.2rem]" />
                                    <span class="sr-only">Settings</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem
                                    :disabled="reportDisabled"
                                    @click="
                                        async () => {
                                            reportDisabled = true;
                                            const { data } = await axios.post(`/api/posts/${post.id}/report`);

                                            if (!data.success) {
                                                toast.error(data.message);
                                                return;
                                            }

                                            await refetch();
                                            toast.success(data.message);
                                            reportDisabled = false;
                                        }
                                    "
                                    >Report {{ post.reports_count }} / {{ post.report_threshold }}</DropdownMenuItem
                                >
                                <DropdownMenuItem
                                    v-if="post.user_id === user?.id"
                                    @click="
                                        () => {
                                            selected = { id: post.id, title: post.title, description: post.description };
                                            open = 'upsert';
                                        }
                                    "
                                    >Update</DropdownMenuItem
                                >
                                <DropdownMenuItem
                                    v-if="post.user_id === user?.id"
                                    @click="
                                        () => {
                                            selected = { id: post.id, title: post.title, description: post.description };
                                            open = 'delete';
                                        }
                                    "
                                    >Delete</DropdownMenuItem
                                >
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
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
                    @commentsClick="() => setSelectedPost({ id: post.id, totalResponses: post.comments_count })"
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
