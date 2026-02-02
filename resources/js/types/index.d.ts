import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';
import { CorrelationType } from './enums';

export { CorrelationType };

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    children?: NavItem[];
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
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Religion {
    id: number;
    name: string;
    description: string | null;
    approved: boolean;
    parent_id: number | null;
    created_at: string;
    updated_at: string;

    doctrines?: Doctrine[];
    descendants?: Religion[]
}

export type ReligionForm = Pick<Religion, 'name' | 'description' | 'parent_id'>

export interface Doctrine {
    id: number;
    title: string;
    description: string | null;
    created_at: string;
    updated_at: string;
}

export interface ItemData {
    id: number;
    type: string;
    title: string;
    description?: string | null;
}

export type DoctrineForm = Pick<Doctrine, 'title' | 'description'>

export interface Correlation {
    correlatable_from_type: string;
    correlatable_from_id: number;
    correlatable_to_type: string;
    correlatable_to_id: number;
    description: string | null;
    type: CorrelationType;
    created_at: string;
    updated_at: string;
}

export type CorrelationForm = Pick<Correlation, 'correlatable_to_type' | 'correlatable_to_id' | 'description' | 'type'>

export interface SimplePaginated<T> {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number | null;
        path: string;
        per_page: number;
        to: number | null;
    };
}

export type BreadcrumbItemType = BreadcrumbItem;
