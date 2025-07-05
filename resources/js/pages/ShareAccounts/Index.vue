<template>
    <Head title="Share Accounts" />
    <AppLayout :breadcrumbs="breadcrumbs">
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
                <Link href="/share-accounts/create">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Add Share Account
                    </button>
                </Link>
            </div>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-3 text-center">#</th>
                            <th class="py-3 px-3 text-center">Account Number</th>
                            <th class="py-3 px-3 text-center">Member ID</th>
                            <th class="py-3 px-3 text-center">Balance</th>
                            <th class="py-3 px-3 text-center">Employer Name</th>
                            <th class="py-3 px-3 text-center">Employer Address</th>
                            <th class="py-3 px-3 text-center">Employer Email</th>
                            <th class="py-3 px-3 text-center">Employer Phone</th>
                            <th class="py-3 px-3 text-center">Nominee ID</th>
                            <th class="py-3 px-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(account, index) in props.shareAccounts" :key="account.id">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.share_account_number }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.member_id }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.balance }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.employer_name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.employer_address }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.employer_email }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.employer_phone }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ account.nominee_id }}</td>
                            <td class="border border-gray-300 text-center py-6 flex justify-center space-x-2">
                                <!-- Actions: Add edit/delete as needed -->
                                <!-- <Link :href="`/share-accounts/${account.id}/edit`" class="text-green-500 hover:text-green-700">
                                    <EditIcon class="h-5 w-5" />
                                </Link> -->
                                <!-- <DeleteModal ... /> -->
                            </td>
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
import { ref } from 'vue';

const props = defineProps<{
    shareAccounts: Array<{
        id: number;
        share_account_number: string;
        member_id: number;
        balance: number;
        employer_name: string | null;
        employer_address: string | null;
        employer_email: string | null;
        employer_phone: string | null;
        nominee_id: number | null;
    }>
}>();

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

const breadcrumbs = [
    {
        title: 'Share Accounts',
        href: '/share-accounts',
    },
];
</script>
