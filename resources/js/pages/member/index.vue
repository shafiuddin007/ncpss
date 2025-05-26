<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { defineProps, ref } from 'vue';
import { EyeIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import DeleteModal from '@/components/DeleteModal.vue';
import IndexTable from '@/components/ui/table/memberIndexTable.vue';

const props = defineProps({
    members: {
        type: Array as () => Array<{ id: number; name: string; father_name: string; mother_name: string; nid: string; mobile: string }>,
        required: true,
    },
});

const processing = ref(false);

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
    closeModal();
    processing.value = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch(routePath, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
        .then(async (response) => {
            const data = await response.json();
            if (response.ok) {
                closeModal();
                showAlert('success', data.message || 'Member deleted successfully.');
                location.reload();
            } else {
                closeModal();
                showAlert('error', data.message || 'Failed to delete the member. Please try again.');
            }
        })
        .catch((error) => {
            closeModal();
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

// Table columns config for IndexTable
const columns = [
    { key: 'name', label: 'NAME', thClass: 'border border-gray-300 px-4 py-2' },
    { key: 'father_name', label: "FATHER'S NAME", thClass: 'border border-gray-300 px-4 py-2' },
    { key: 'mother_name', label: "MOTHER'S NAME", thClass: 'border border-gray-300 px-4 py-2' },
    { key: 'nid', label: 'NID', thClass: 'border border-gray-300 px-4 py-2' },
    { key: 'action', label: 'ACTION', thClass: 'border border-gray-300 px-4 py-2' },
];

const rows = props.members.map((member: any) => ({
    ...member,
    // Provide dummy avatar and email for compatibility with IndexTable
    avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent(member.name),
    mobile: member.mobile,
    father_name: member.father_name || '',
    mother_name: member.mother_name || '',
    nid: member.nid || '',
    action: member.id,
}));

const showDeleteModal = ref(false);
const memberToDelete = ref<{ id: number; name: string } | null>(null);

function openDeleteModal(row: any) {
    memberToDelete.value = { id: row.id, name: row.name };
    showDeleteModal.value = true;
}
function closeDeleteModal() {
    showDeleteModal.value = false;
    memberToDelete.value = null;
}
function confirmDelete() {
    if (memberToDelete.value) {
        handleDelete(`/members/${memberToDelete.value.id}`, closeDeleteModal);
    }
}
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

        <div>
            <IndexTable
                :title="'Member List'"
                :subtitle="'Search members'"
                :columns="columns"
                :rows="rows"
                :departments="[]"
                :pageSize="10"
                :hasActions="true"
                @view="row => router.visit(`/members/${row.id}`)"
                @edit="row => router.visit(`/members/${row.id}/edit`)"
                @delete="openDeleteModal"
                @add="() => router.visit('/members/create')"
            />
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-40 min-h-full overflow-y-auto overflow-x-hidden transition flex items-center">
            <!-- overlay -->
            <div aria-hidden="true" class="fixed inset-0 w-full h-full bg-black/50 cursor-pointer" @click="closeDeleteModal"></div>
            <!-- Modal -->
            <div class="relative w-full cursor-pointer pointer-events-none transition my-auto p-4">
                <div class="w-full py-2 bg-white cursor-default pointer-events-auto dark:bg-gray-800 relative rounded-xl mx-auto max-w-sm">
                    <button tabindex="-1" type="button" class="absolute top-2 right-2" @click="closeDeleteModal">
                        <svg title="Close" tabindex="-1" class="h-4 w-4 cursor-pointer text-gray-400"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close</span>
                    </button>
                    <div class="space-y-2 p-2">
                        <div class="p-4 space-y-2 text-center dark:text-white">
                            <h2 class="text-xl font-bold tracking-tight" id="page-action.heading">
                                Delete {{ memberToDelete?.name }}
                            </h2>
                            <p class="text-gray-500">
                                Are you sure you want to delete this member?
                            </p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div aria-hidden="true" class="border-t dark:border-gray-700 px-2"></div>
                        <div class="px-6 py-2">
                            <div class="grid gap-2 grid-cols-[repeat(auto-fit,minmax(0,1fr))]">
                                <button type="button"
                                    class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50"
                                    @click="closeDeleteModal">
                                    <span class="flex items-center gap-1">
                                        <span>Cancel</span>
                                    </span>
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow border-transparent bg-red-600 hover:bg-red-500 focus:bg-red-700"
                                    @click="confirmDelete">
                                    <span class="flex items-center gap-1">
                                        <span>Confirm</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>