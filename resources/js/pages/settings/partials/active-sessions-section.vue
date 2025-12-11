<script setup lang="ts">
import Heading from '@/components/heading.vue';
import { getApiData } from '@/lib/utils';
import { useQuery } from '@tanstack/vue-query';

const { data } = useQuery({
    queryKey: ['useUser'],
    queryFn: async () => getApiData('/user'),
    staleTime: 1000 * 60 * 5,
});

// const cos = usePage().props.auth;

console.log(data);
</script>

<template>
    <div class="flex flex-col space-y-6 sm:px-6 md:px-8 lg:px-10">
        {{ data }}
        <!-- <div v-for="session in data" :key="session.id">
            {{ session }}
        </div> -->
        <Heading
            title="Active sessions"
            description="View and manage devices currently signed in to your account."
        />
    </div>
</template>
