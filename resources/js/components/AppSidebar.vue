<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, BoxIcon, Users2, FileText } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();
const userRole = computed<string>(() => (page.props.role as string) || 'member');
console.log('User Role:', userRole.value);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        roles: [
            'admin',
            'member',
            'loan committee member',
            'loan committee secretary',
            'loan committee chairman',
            'managing committee secretary'
        ],
    },
    {
        title: 'Members',
        href: '/members',
        icon: Users2,
        roles: [
            'admin',
            'loan committee member',
            'loan committee secretary',
            'loan committee chairman',
            'managing committee secretary' // <-- add this role
        ],
    }, 
    {
        title: 'Product',
        href: '/products',
        icon: BoxIcon,
        roles: [
            'admin',
            'member'
            // 'loan committee member' removed
        ],
    }, 
    {
        title: 'Applications',
        href: '/applications',
        icon: FileText,
        roles: [
            'admin',
            'loan committee member',
            'loan committee secretary',
            'loan committee chairman',
            'managing committee secretary' // <-- add this role
        ],
    },
    {
        title: 'Relationships',
        href: '/realationships',
        icon: LayoutGrid,
        roles: [
            'admin'
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];

// Show all menus if admin, otherwise filter by roles
const filteredMainNavItems = computed(() =>
    userRole.value === 'admin'
        ? mainNavItems
        : mainNavItems.filter(item => !item.roles || item.roles.includes(userRole.value))
);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="filteredMainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
