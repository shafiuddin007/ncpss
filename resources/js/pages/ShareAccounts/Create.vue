<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';

const breadcrumbs = [
    { title: 'Share Accounts', href: '/share-accounts' },
    { title: 'Create Share Account', href: '' },
];

const props = defineProps({
    member: {
        type: Object,
        default: null
    },
    nominee: {
        type: Object,
        default: null
    }
});

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

const nomineeEdit = ref(false);

// If member info is provided, prefill form fields
if (props.member) {
    form.member_id = props.member.id || '';
    // Optionally prefill other fields from member
}
if (props.nominee) {
    form.nominee_id = props.nominee.id || '';
    // Optionally prefill other fields from nominee
}

console.log('Nominee:', props.nominee);

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
                    <div class="flex-1">
                        <Label for="balance">Balance</Label>
                        <Input id="balance" v-model="form.balance" type="number" step="0.01" required placeholder="Balance" />
                        <InputError :message="form.errors.balance" />
                    </div>
                </div>

                <!-- Employer Details Section -->
                <div class="border rounded-lg p-4 bg-gray-50 mt-4">
                    <h3 class="text-md font-semibold mb-4">Employer Details</h3>
                    <div class="flex gap-6 mb-4">
                        <div class="flex-1">
                            <Label for="employer_name">Employer Name</Label>
                            <Input id="employer_name" v-model="form.employer_name" type="text" placeholder="Employer Name" />
                            <InputError :message="form.errors.employer_name" />
                        </div>
                        <div class="flex-1">
                            <Label for="employer_address">Employer Address</Label>
                            <Input id="employer_address" v-model="form.employer_address" type="text" placeholder="Employer Address" />
                            <InputError :message="form.errors.employer_address" />
                        </div>
                    </div>
                    <div class="flex gap-6">
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
                </div>
                <!-- End Employer Details Section -->

                <!-- Nominee Section -->
                <div class="border rounded-lg p-4 bg-gray-50 mt-4">
                    <h3 class="text-md font-semibold mb-4">Nominee Information</h3>
                    <div v-if="props.nominee && !nomineeEdit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <span class="font-semibold">Name:</span> {{ props.nominee.nominee_name }}
                            </div>
                            <div>
                                <span class="font-semibold">Relationship:</span> {{ props.nominee.relationship }}
                            </div>
                            <div>
                                <span class="font-semibold">Age:</span> {{ props.nominee.age }}
                            </div>
                            <div>
                                <span class="font-semibold">Contact No:</span> {{ props.nominee.contact_no }}
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-semibold">Address:</span> {{ props.nominee.address }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-2">
                        <input id="edit_nominee" type="checkbox" v-model="nomineeEdit" class="mr-2" />
                        <Label for="edit_nominee">Do you want to change the nominee info?</Label>
                    </div>
                    <div v-if="nomineeEdit" class="mt-4">
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="nominee_name">Nominee Name</Label>
                                <Input id="nominee_name" v-model="form.nominee_name" type="text" placeholder="Nominee Name" />
                                <InputError :message="form.errors.nominee_name" />
                            </div>
                            <div class="flex-1">
                                <Label for="relationship">Relationship</Label>
                                <Input id="relationship" v-model="form.relationship" type="text" placeholder="Relationship" />
                                <InputError :message="form.errors.relationship" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="age">Age</Label>
                                <Input id="age" v-model="form.age" type="number" placeholder="Age" />
                                <InputError :message="form.errors.age" />
                            </div>
                            <div class="flex-1">
                                <Label for="contact_no">Contact No</Label>
                                <Input id="contact_no" v-model="form.contact_no" type="text" placeholder="Contact No" />
                                <InputError :message="form.errors.contact_no" />
                            </div>
                        </div>
                        <div>
                            <Label for="address">Address</Label>
                            <Input id="address" v-model="form.address" type="text" placeholder="Address" />
                            <InputError :message="form.errors.address" />
                        </div>
                    </div>
                </div>
                <!-- End Nominee Section -->

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
                    
