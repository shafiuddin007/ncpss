<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'; // <-- Use your main layout, not AppSidebar.vue
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';


const props = defineProps<{
    role: any,
    permissions: Array<any>
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Role Permissions', href: '/roles-permissions' },
    { title: 'Edit Role Permission', href: '' },
];
const form = useForm({
    role_id: props.role.id,
    permissions: props.role.permissions ? props.role.permissions.map((p: any) => p.name) : [],
});

function submit() {
    form.post(route('roles.permissions.update'), { preserveScroll: true });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Edit Role Permissions" />
        <div class="m-6 max-w-xl">
            <h1 class="text-2xl font-bold mb-6">Edit Permissions for "{{ role.name }}"</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <div class="grid gap-2">
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                :id="'perm-' + permission.id"
                                :value="permission.name"
                                v-model="form.permissions"
                            />
                            <label :for="'perm-' + permission.id">{{ permission.name }}</label>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white" :disabled="form.processing">Update</Button>
                    <Button as="a" :href="route('roles.permissions.index')" class="btn bg-black text-white" type="button">Cancel</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
