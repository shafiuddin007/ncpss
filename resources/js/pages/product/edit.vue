<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import Dropdown from '@/components/ui/Dropdown.vue';
import ActiveCheckbox from '@/components/ui/checkbox/ActiveCheckbox.vue';

// Props
const props = defineProps({
    product: {
        type: Object as () => {
            id: number;
            name: string;
            type: string;
            description: string;
            interest_rate: number;
            min_balance: number;
            max_loan_amount: number;
            loan_term_months: number;
            currency: string;
            is_active: boolean;
        },
        required: true,
    },
});

// Dropdown options
const productTypes = [
    { value: 'Savings', label: 'Savings' },
    { value: 'Loan', label: 'Loan' },
    { value: 'Credit card', label: 'Credit Card' },
    { value: 'Investment', label: 'Investment' },
];

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: '/products' },
    { title: 'Edit Product', href: '' },
];

// Form setup
const form = useForm({
    name: props.product.name,
    type: props.product.type || '',
    description: props.product.description,
    interest_rate: props.product.interest_rate,
    min_balance: props.product.min_balance,
    max_loan_amount: props.product.max_loan_amount,
    loan_term_months: props.product.loan_term_months,
    currency: props.product.currency,
    is_active: props.product.is_active ?? false,
});

// Handle submit
const handleSubmit = () => {
    console.log('Submitting:', form.data());
    form.is_active = !!form.is_active; // force boolean
    form.put(`/products/${props.product.id}`);
};


</script>

<template>

    <Head title="Edit Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6">
            <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 gap-6">
                    <div class="flex gap-6">
                        <!-- Name Field -->
                        <div class="flex-1">
                            <Label for="name">Name</Label>
                            <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                                placeholder="Full name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Dropdown Field -->
                        <div class="flex-1">
                            <Dropdown id="type" label="Product Type" :options="productTypes" v-model="form.type"
                                placeholder="Select Product Type" :error="form.errors.type" />
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div>
                        <Label for="description">Description</Label>
                        <Input id="description" type="text" autocomplete="description"
                            v-model="form.description" placeholder="Description" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Interest Rate Field -->
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="interest_rate">Interest Rate (%)</Label>
                            <Input id="interest_rate" type="number" step="0.01" v-model="form.interest_rate" />
                            <InputError :message="form.errors.interest_rate" />
                        </div>

                        <!-- Min Balance Field -->
                        <div class="flex-1">
                            <Label for="min_balance">Min Balance</Label>
                            <Input id="min_balance" type="number" step="0.01" v-model="form.min_balance" />
                            <InputError :message="form.errors.min_balance" />
                        </div>

                        <!-- Max Loan Amount Field -->
                        <div class="flex-1">
                            <Label for="max_loan_amount">Max Loan Amount</Label>
                            <Input id="max_loan_amount" type="number" step="0.01" v-model="form.max_loan_amount" />
                            <InputError :message="form.errors.max_loan_amount" />
                        </div>

                    </div>

                    <!-- Loan Term Field -->
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="loan_term_months">Loan Term (Months)</Label>
                            <Input id="loan_term_months" type="number" v-model="form.loan_term_months" />
                            <InputError :message="form.errors.loan_term_months" />
                        </div>

                        <!-- Currency Field -->
                        <div class="flex-1">
                            <Label for="currency">Currency</Label>
                            <Input id="currency" type="text" maxlength="3" v-model="form.currency" required />
                            <InputError :message="form.errors.currency" />
                        </div>
                        
                        <!-- Active Checkbox -->
                        <div class="flex-1 flex items-center">
                            <Label for="is_active" class="mr-2">Active</Label>
                            <ActiveCheckbox id="is_active" v-model="form.is_active" class="mt-1" />
                            <InputError :message="form.errors.is_active" />
                        </div>
                    </div>

                </div>

                <!-- Buttons -->
                <div class="mt-6 flex justify-end">
                    <Link href="/products" class="mr-4">
                    <Button variant="destructive">Cancel</Button>
                    </Link>
                    <Button variant="submit" :disabled="form.processing">Save Changes</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
