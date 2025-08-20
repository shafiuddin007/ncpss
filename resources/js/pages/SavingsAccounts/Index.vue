<template>
    <Head title="Savings Accounts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-end mb-6">
                <Link href="/savings-accounts/create">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Add Savings Account
                    </button>
                </Link>
            </div>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-3 text-center">#</th>
                            <th class="py-3 px-3 text-center">Account Number</th>
                            <th class="py-3 px-3 text-center">Member PIN</th>
                            <th class="py-3 px-3 text-center">Member Name</th>
                            <th class="py-3 px-3 text-center">Nominee Name</th>
                            <th class="py-3 px-3 text-center">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(account, index) in props.savingsAccounts" :key="account.id">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.savings_account_number }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.member?.pin ?? '' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.member?.name ?? '' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.nominee?.nominee_name ?? '' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.balance ?? account.initial_deposit }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    savingsAccounts: Array<{
        id: number;
        savings_account_number: string;
        member_id: number;
        balance?: number;
        initial_deposit?: number;
        member?: {
            pin?: string;
            name?: string;
        };
        nominee?: {
            nominee_name?: string;
        };
    }>
}>();

const breadcrumbs = [
    {
        title: 'Savings Accounts',
        href: '/savings-accounts',
    },
];
</script>
