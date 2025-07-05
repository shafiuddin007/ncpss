<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';

const breadcrumbs = [
    { title: 'Share Accounts', href: '/share-accounts' },
    { title: 'Create Share Account', href: '' },
];

const form = useForm({
    member_id: '',
    share_account_number: '',
    balance: '',
    employer_name: '',
    employer_address: '',
    employer_email: '',
    employer_phone: '',
    nominee_id: '',
});

const handleSubmit = () => {
    form.post('/share-accounts', {
        onSuccess: () => {
            router.visit('/share-accounts');
        },
    });
};
</script>

<template>
    <Head title="Create Share Account" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
                <h2 class="text-lg font-semibold">Share Account Information</h2>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="member_id">Member ID</Label>
                        <Input id="member_id" v-model="form.member_id" type="number" required placeholder="Member ID" />
                        <InputError :message="form.errors.member_id" />
                    </div>
                    <div class="flex-1">
                        <Label for="share_account_number">Account Number</Label>
                        <Input id="share_account_number" v-model="form.share_account_number" type="text" required placeholder="Account Number" />
                        <InputError :message="form.errors.share_account_number" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="balance">Balance</Label>
                        <Input id="balance" v-model="form.balance" type="number" step="0.01" required placeholder="Balance" />
                        <InputError :message="form.errors.balance" />
                    </div>
                    <div class="flex-1">
                        <Label for="employer_name">Employer Name</Label>
                        <Input id="employer_name" v-model="form.employer_name" type="text" placeholder="Employer Name" />
                        <InputError :message="form.errors.employer_name" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="employer_address">Employer Address</Label>
                        <Input id="employer_address" v-model="form.employer_address" type="text" placeholder="Employer Address" />
                        <InputError :message="form.errors.employer_address" />
                    </div>
                    <div class="flex-1">
                        <Label for="employer_email">Employer Email</Label>
                        <Input id="employer_email" v-model="form.employer_email" type="email" placeholder="Employer Email" />
                        <InputError :message="form.errors.employer_email" />
                    </div>
                    <div class="flex-1">
                        <Label for="employer_phone">Employer Phone</Label>
                        <Input id="employer_phone" v-model="form.employer_phone" type="text" placeholder="Employer Phone" />
                        <InputError :message="form.errors.employer_phone" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="nominee_id">Nominee ID</Label>
                        <Input id="nominee_id" v-model="form.nominee_id" type="number" placeholder="Nominee ID" />
                        <InputError :message="form.errors.nominee_id" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <Link href="/share-accounts" class="mr-4">
                        <Button variant="destructive" type="button">Cancel</Button>
                    </Link>
                    <Button variant="submit" :disabled="form.processing">Create Share Account</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
