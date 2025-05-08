<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

const handleDelete = (routePath: string, closeModal: () => void) => {
    closeModal();
    processing.value = true;

    fetch(routePath, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
    })
        .then((response) => {
            if (response.ok) {
                location.reload();
            } else {
                console.error('Failed to delete the product.');
            }
        })
        .catch((error) => console.error(error))
        .finally(() => (processing.value = false));
};
</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-6">
            <Link href="/products/create">
                <Button>Add Product</Button>
            </Link>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">#</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Type</th>
                        <th class="border border-gray-300 px-4 py-2">Interest Rate (%)</th>
                        <th class="border border-gray-300 px-4 py-2">Min Balance</th>
                        <th class="border border-gray-300 px-4 py-2">Max Loan Amount</th>
                        <th class="border border-gray-300 px-4 py-2">Loan Term (Months)</th>
                        <th class="border border-gray-300 px-4 py-2">Currency</th>
                        <th class="border border-gray-300 px-4 py-2">Active</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, index) in products" :key="product.id">
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ product.name }}</td>
                        <td class="border border-gray-300 px-4 py-2 capitalize">{{ product.type }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            {{ typeof product.interest_rate === 'number' ? product.interest_rate.toFixed(2) : 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            {{ typeof product.min_balance }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-right">
                            {{ typeof product.max_loan_amount === 'number' ? product.max_loan_amount.toFixed(2) : 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            {{ product.loan_term_months !== null ? product.loan_term_months : 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ product.currency }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <span
                                :class="product.is_active ? 'text-green-600' : 'text-red-600'"
                                class="font-bold"
                            >
                                {{ product.is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center flex justify-center space-x-2">
                            <Link :href="`/products/${product.id}`" class="text-blue-500 hover:text-blue-700">
                                <EyeIcon class="h-5 w-5" />
                            </Link>
                            <Link :href="`/products/${product.id}/edit`" class="text-green-500 hover:text-green-700">
                                <EditIcon class="h-5 w-5" />
                            </Link>
                            <DeleteModal
                                :onConfirm="(closeModal: () => void) => handleDelete(`/products/${product.id}`, closeModal)"
                                :processing="processing"
                                title="Delete Product"
                                description="Are you sure you want to delete this product? This action cannot be undone."
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