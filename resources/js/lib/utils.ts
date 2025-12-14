import { InertiaLinkProps } from '@inertiajs/vue3';
import axios from 'axios';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export const toBase64 = (file: File): Promise<string> =>
    new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result as string);
        reader.onerror = reject;
    });

export type Session = {
    id: string;
    user_id: number | null;
    ip_address: string | null;
    user_agent: string | null;
    payload: string;
    last_activity: number;
    message: string;
    success?: boolean;
};

type User = {
    id: number;
    user_name: string;
    email: string | null;
    remember_token?: string;
    created_at: string;
    updated_at: string;
};

export async function getApiData<T extends '/user-sessions' | '/user'>(url: T) {
    const { data } = await axios(`api${url}`);
    return data as unknown as T extends '/user-sessions' ? Session[] : User;
}
