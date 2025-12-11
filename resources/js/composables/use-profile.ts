import { fetchWithXsrf } from '@/lib/utils';
import { defineStore } from 'pinia';
import { computed, onMounted, ref } from 'vue';

// create a composable, maybe in lib folder

export const useProfileStore = defineStore('profile', () => {
    const userResponse = ref<any | null>(null);

    async function fetchUser() {
        const res = await (await fetchWithXsrf('/api/user')).json();
        userResponse.value = res;
    }

    // async function createInvitation(
    //     inviteBody: CreateInvitationBody,
    // ): Promise<undefined> {
    //     const organization = getCurrentOrganizationId();
    //     if (organization) {
    //         await handleApiRequestNotifications(
    //             () =>
    //                 api.invite(inviteBody, {
    //                     params: {
    //                         organization: organization,
    //                     },
    //                 }),
    //             'User successfully invited',
    //             'Failed to invite user',
    //         );
    //         await fetchInvitations();
    //     }
    // }

    const user = computed(() => {
        return userResponse.value;
    });

    onMounted(() => {
        fetchUser();
    });

    return { user };
});
