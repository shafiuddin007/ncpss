<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

defineProps<{
    users: Array<any>
}>();

const showConfirm = ref(false);
const userToDelete = ref<number|null>(null);

function confirmDelete(id: number) {
    userToDelete.value = id;
    showConfirm.value = true;
}

function destroyConfirmed() {
    if (userToDelete.value !== null) {
        router.delete(route('users.destroy', userToDelete.value));
        showConfirm.value = false;
        userToDelete.value = null;
    }
}

function cancelDelete() {
    showConfirm.value = false;
    userToDelete.value = null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

</script>

<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        
        


        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-end mb-6">
                <Link :href="route('users.create')">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Add
                    User</button>
                </Link>
            </div>
            
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Email</th>
                        <th class="px-4 py-2 border">Role</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="px-4 py-2 border">{{ user.name }}</td>
                        <td class="px-4 py-2 border">{{ user.email }}</td>
                        <td class="px-4 py-2 border">
                            <span v-if="user.roles && user.roles.length">{{ user.roles[0].name }}</span>
                        </td>
                        <td class="px-4 py-2 border">
                            <Link
                                :href="route('users.edit', user.id)"
                                class="inline-flex items-center btn btn-sm btn-secondary mr-2"
                                title="Edit"
                            >
                                <Pencil class="w-4 h-4" style="color: #2563eb;" />
                            </Link>
                            <button
                                @click="confirmDelete(user.id)"
                                class="inline-flex items-center btn btn-sm btn-danger"
                                title="Delete"
                            >
                                <Trash2 class="w-4 h-4" style="color: #dc2626;" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Confirmation Popup -->
        <div v-if="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
            <div class="bg-white rounded shadow-lg p-6 w-full max-w-sm">
                <div class="mb-4 text-lg font-semibold">Are you sure you want to delete this user?</div>
                <div class="flex justify-end gap-2">
                    <Button @click="cancelDelete" class="btn btn-secondary" type="button">Cancel</Button>
                    <Button @click="destroyConfirmed" class="btn btn-danger bg-red-600 hover:bg-red-700 text-white" type="button">Delete</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
