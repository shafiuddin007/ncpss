<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { defineProps, ref } from 'vue';
import { EyeIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import DeleteModal from '@/components/DeleteModal.vue';

const props = defineProps({
    members: {
        type: Array as () => Array<{ id: number; name: string; father_name: string; mother_name: string; nid: string; mobile: string }>,
        required: true,
    },
});

const processing = ref(false);

// Local inline alert state
const alert = ref({
    type: '', // 'success' | 'error'
    message: '',
    visible: false,
});

const showAlert = (type: 'success' | 'error', message: string) => {
    alert.value = { type, message, visible: true };
    setTimeout(() => (alert.value.visible = false), 4000);
};

const handleDelete = (routePath: string, closeModal: () => void) => {
    // Close the modal immediately
    closeModal();

    processing.value = true;

    // Retrieve the CSRF token from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(routePath, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token
        },
    })
        .then(async (response) => {
            const data = await response.json();
            //location.reload();
            if (response.ok) {
                closeModal();
                // Show success message
                showAlert('success', data.message || 'Member deleted successfully.');

                // Refresh the page
                location.reload();
            } else {
                closeModal();
                // Show error message
                showAlert('error', data.message || 'Failed to delete the member. Please try again.');
            }
        })
        .catch((error) => {
            closeModal();
            // Show error message for unexpected errors
            showAlert('error', 'An unexpected error occurred. Please try again.');
            console.error(error);
        })
        .finally(() => {
            processing.value = false;
        });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Member',
        href: '/members',
    },
];
</script>

<template>
    <Head title="Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Inline alert (no redirect) -->
        <div
            v-if="alert.visible"
            :class="[
                'flex items-center p-4 mb-4 text-sm border rounded-lg',
                alert.type === 'success'
                    ? 'text-green-800 bg-green-50 border-green-300'
                    : 'text-red-800 bg-red-50 border-red-300'
            ]"
            role="alert"
        >
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4h2v2H9v-2zm0-8h2v6H9V6z" />
            </svg>
            <div>
                <span class="font-medium">{{ alert.type === 'success' ? 'Success' : 'Error' }}:</span>
                {{ alert.message }}
            </div>
        </div>

        <div class="flex flex-col space-y-6">
            <div class="flex justify-end">
                <Link href="members/create" class="w-40">
                    <Button class="w-40">Add Member</Button>
                </Link>
            </div>
        </div>

        <div class="m-10">
            <h1 class="text-2xl font-bold mb-6">Members List</h1>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">#</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Father's Name</th>
                        <th class="border border-gray-300 px-4 py-2">Mother's Name</th>
                        <th class="border border-gray-300 px-4 py-2">NID</th>
                        <th class="border border-gray-300 px-4 py-2">Mobile</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(member, index) in members" :key="member.id">
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ member.name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ member.father_name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ member.mother_name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ member.nid }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ member.mobile }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center flex justify-center space-x-2">
                            <Link :href="`/members/${member.id}`" class="text-blue-500 hover:text-blue-700">
                                <EyeIcon class="h-5 w-5" />
                            </Link>
                            <Link :href="`/members/${member.id}/edit`" class="text-green-500 hover:text-green-700">
                                <EditIcon class="h-5 w-5" />
                            </Link>
                            <DeleteModal
                                :onConfirm="(closeModal: () => void) => handleDelete(`/members/${member.id}`, closeModal)"
                                :processing="processing"
                                title="Delete Member"
                                description="Are you sure you want to delete this member? This action cannot be undone."
                                confirmText="Delete"
                                cancelText="Cancel"
                            >
                                <template #trigger>
                                    <TrashIcon class="h-5 w-5 text-red-500 hover:text-red-700 cursor-pointer" />
                                </template>
                            </DeleteModal>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>