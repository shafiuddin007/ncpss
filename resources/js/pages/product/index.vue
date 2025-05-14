<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import { EyeIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import DeleteModal from '@/components/DeleteModal.vue';

const props = defineProps({
    products: {
        type: Array as () => Array<{
            id: number;
            name: string;
            type: string;
            description: string;
            interest_rate: number | null;
            min_balance: number | null;
            max_loan_amount: number | null;
            loan_term_months: number | null;
            currency: string;
            is_active: boolean;
        }>,
        required: true,
    },
});

const processing = ref(false);

// Inline alert state
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
                showAlert('success', data.message || 'Product deleted successfully.');
                // Optionally, you can remove the product from the list here instead of reloading
                location.reload();
            } else {
                showAlert('error', data.message || 'Failed to delete the product. Please try again.');
            }
        })
        .catch((error) => {
            showAlert('error', 'An unexpected error occurred. Please try again.');
            console.error(error);
        })
        .finally(() => {
            processing.value = false;
        });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];
</script>

<template>

    <Head title="Products" />
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
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-end mb-6">
                <Link href="/products/create">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Add
                    Product</button>
                </Link>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-3 text-center">#</th>
                            <th class="py-3 px-3 text-center">Name</th>
                            <th class="py-3 px-3 text-center">Type</th>
                            <th class="py-3 px-3 text-center">Interest Rate (%)</th>
                            <th class="py-3 px-3 text-center">Min Balance</th>
                            <th class="py-3 px-3 text-center">Max Loan Amount</th>
                            <th class="py-3 px-3 text-center">Loan Term (Months)</th>
                            <th class="py-3 px-3 text-center">Currency</th>
                            <th class="py-3 px-3 text-center">Active</th>
                            <th class="py-3 px-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, index) in products" :key="product.id">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ product.name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ product.type }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                {{ product.interest_rate ?? 0 }}%
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                {{ product.min_balance ?? 0 }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                {{ product.max_loan_amount ?? 0 }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                {{ product.loan_term_months ?? 0 }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ product.currency }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span :class="product.is_active ? 'text-green-600' : 'text-red-600'" class="font-bold">
                                    {{ product.is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="border border-gray-300 text-center py-6 flex justify-center space-x-2">
                                <!-- <Link :href="`/products/${product.id}`" class="text-blue-500 hover:text-blue-700">
                                <EyeIcon class="h-5 w-5" />
                                </Link> -->
                                <Link :href="`/products/${product.id}/edit`"
                                    class="text-green-500 hover:text-green-700">
                                <EditIcon class="h-5 w-5" />
                                </Link>
                                <DeleteModal
                                    :onConfirm="(closeModal: () => void) => handleDelete(`/products/${product.id}`, closeModal)"
                                    :processing="processing" title="Delete Product"
                                    description="Are you sure you want to delete this product? This action cannot be undone."
                                    confirmText="Delete" cancelText="Cancel">
                                    <template #trigger>
                                        <TrashIcon class="h-5 w-5 text-red-500 hover:text-red-700 cursor-pointer" />
                                    </template>
                                </DeleteModal>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>