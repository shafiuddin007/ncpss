<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Dropdown from '@/components/ui/Dropdown.vue';
import { Button } from '@/components/ui/button';

defineProps<{
    roles: Array<any>
}>();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'Add User', href: '' },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

function submit() {
    form.post(route('users.store'));
}
</script>

<template>
    <Head title="Add User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        
        <div class="m-6">
            <h1 class="text-2xl font-bold mb-6">ADD User</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-md">
                <div>
                    <label for="name">Name</Label>
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
                     required />
                    <div class="text-red-500 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>
                <div>
                    <Label for="password">Password</Label>
                    <Input v-model="form.password" type="password" class="input" required />
                    <div class="text-red-500 text-sm" v-if="form.errors.password">{{ form.errors.password }}</div>
                </div>
                <div>
                    <Label>Confirm Password</Label>
                    <Input v-model="form.password_confirmation" type="password" class="input" required />
                </div>
                <div>
                    <!-- Fix Dropdown options binding -->
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
                    <Button type="submit" class="btn btn-primary" :disabled="form.processing">Create</Button>
                    <Button as="a" :href="route('users.index')" class="btn btn-secondary" type="button">Cancel</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
