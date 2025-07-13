<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Dropdown from '@/components/ui/Dropdown.vue';
import { Button } from '@/components/ui/button';

// Fix: use defineProps and assign to a variable (props)
const props = defineProps<{
    user: any,
    roles: Array<any>
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.roles && props.user.roles.length ? props.user.roles[0].name : '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Edit User', href: '' },
];

function submit() {
    form.put(route('users.update', props.user.id));
}
</script>

<template>
    <Head title="Edit User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6">
            <h1 class="text-2xl font-bold mb-6">Edit User</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-md">
                <div>
                    <Label for="name">Name</Label>
                    <Input
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                        v-model="form.name"
                    />
                    <div class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div>
                    <Label for="email">Email</Label>
                    <Input
                        type="email"
                        v-model="form.email"
                        placeholder="example@example.com"
                        required
                        readonly
                    />
                    <div class="text-red-500 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>
                <div>
                    <Label for="password">Password (leave blank to keep current)</Label>
                    <Input v-model="form.password" type="password" class="input" />
                    <div class="text-red-500 text-sm" v-if="form.errors.password">{{ form.errors.password }}</div>
                </div>
                <div>
                    <Label>Confirm Password</Label>
                    <Input v-model="form.password_confirmation" type="password" class="input" />
                </div>
                <div>
                    <Dropdown
                        id="role"
                        label="Role"
                        :options="roles.map(role => ({ label: role.name, value: role.name }))"
                        v-model="form.role"
                        placeholder="Select Role"
                        :error="form.errors.role"
                    />
                </div>
                <div class="flex gap-2">
                    <Button type="submit" class="btn btn-primary" :disabled="form.processing">Update</Button>
                    <Button as="a" :href="route('users.index')" class="btn btn-secondary" type="button">Cancel</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
