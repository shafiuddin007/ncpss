<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

defineProps<{
    roles: Role[],
    permissions: Permission[]
}>();
import { Head, Link } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Role Permissions', href: '/roles-permissions' },
];

</script>

<template>
    <Head title="Role Permissions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6">
            <h1 class="text-2xl font-bold mb-6">Role Permissions</h1>
            <table class="min-w-full bg-white border mb-6">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Role</th>
                        <th class="px-4 py-2 border">Permissions</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td class="px-4 py-2 border"> {{role.name}} </td>
                        <td class="px-4 py-2 border">
                            <span v-if="role.permissions && role.permissions.length">
                                {{ role.permissions.map(p => p.name).join(', ') }}
                            </span> 
                            <span v-else class="text-gray-400">No permissions</span>
                        </td>
                        <td class="px-4 py-2 border">
                            <Link :href="route('roles.permissions.edit', { role_id: role.id })" class="inline-flex items-center btn btn-sm btn-primary" title="Edit">
                                <Pencil class="w-4 h-4" style="color: #2563eb;" />
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
                