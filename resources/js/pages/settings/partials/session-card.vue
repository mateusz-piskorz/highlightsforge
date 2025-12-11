<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
// import { Session } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
// const {
//     id,
//     ip_address,
//     last_activity,
//     message,
//     payload,
//     success,
//     user_agent,
//     user_id,
// } = defineProps<Session>();

const { auth } = usePage().props;
console.log(auth);

// const useragent = new UAParser(user_agent).getResult();
// const isMobileDevice = ['mobile', 'tablet'].includes(
//     useragent.device.type || '',
// );
// const deviceName = isMobileDevice
//     ? useragent.device.model
//     : useragent.browser.name;
</script>

<template>
    <Card>
        <CardContent
            className="space-y-4 md:flex md:items-center md:justify-between"
        >
            <div>
                <div className="mb-5 flex items-center gap-4">
                    <!-- <span
                        v-if="isCurrentSession"
                        className="flex h-3 w-3 rounded-full bg-green-500"
                    /> -->

                    <!-- <Smartphone v-if="isMobileDevice" /> -->
                    <!-- <Monitor v-else /> -->

                    <!-- {{ deviceName }}, {{ useragent.os.name }} -->

                    <!-- <p v-if="isCurrentSession" className="text-muted-foreground">(current session)</p> -->
                </div>
                <p className="text-muted-foreground mb-1">
                    End of session {dayjs(session.expiresAt).fromNow()}
                </p>
                <p className="text-muted-foreground">
                    Last activity {dayjs(session.updatedAt).fromNow()}
                </p>
            </div>
            <Button
                disabled="{isCurrentSession}"
                onClick="{handleLogoutSession}"
            >
                Log out
            </Button>
        </CardContent>
    </Card>
</template>
