export interface Auth {
    user: User | null;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    user_name: string;
    email: string | null;
    avatar: string | null;
    created_at: string;
    updated_at: string;
    two_factor_confirmed_at: string | null;
}
