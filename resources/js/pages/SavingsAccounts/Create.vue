<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { ref, computed } from 'vue';

const breadcrumbs = [
    { title: 'Savings Accounts', href: '/savings-accounts' },
    { title: 'Create Savings Account', href: '' },
];

interface RelationshipOption {
    value: string | number;
    label: string;
}

const props = defineProps<{
    member?: object | null;
    nominee?: object | null;
    relationshipOptions: RelationshipOption[];
    savingsAccount?: object | null;
}>();

const form = useForm({
    member_id: '',
    savings_account_number: '',
    initial_deposit: '',
    nominee_id: '',
    nominee_name: '',
    relationship: '',
    age: '',
    contact_no: '',
    address: '',
    scan_image: null as File | null,
});

const nomineeEdit = ref(false);

if (props.member) {
    form.member_id = props.member.id || '';
}
if (props.nominee) {
    form.nominee_id = props.nominee.id || '';
}

const handleSubmit = () => {
    form.post('/savings-accounts', {
        onSuccess: () => {
            router.visit('/savings-accounts');
        },
    });
};

const hasSavingsAccount = computed(() => !!props.savingsAccount);
</script>

<template>
    <Head title="Create Savings Account" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">
            <div v-if="props.member" class="mb-8 p-6 bg-gray-100 border border-gray-200 rounded">
                <h3 class="text-lg font-semibold mb-2">Member Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium">Name:</span>
                        <span> &nbsp;{{ props.member.name }}</span>
                    </div>
                    <div>
                        <span class="font-medium">PIN:</span>
                        <span> &nbsp;{{ props.member.pin }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Father's Name:</span>
                        <span> &nbsp;{{ props.member.father_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Mother's Name:</span>
                        <span> &nbsp;{{ props.member.mother_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium">NID:</span>
                        <span> &nbsp;{{ props.member.nid }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Mobile:</span>
                        <span> &nbsp;{{ props.member.mobile }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Date of Birth:</span>
                        <span> &nbsp;
                            {{
                                props.member.dob
                                    ? new Date(props.member.dob).toLocaleDateString('en-UK', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric'
                                    })
                                    : ''
                            }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium">Gender:</span>
                        <span> &nbsp;{{ props.member.gender }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Address:</span>
                        <span> &nbsp;{{ props.member.pre_address }}</span>
                    </div>
                </div>
            </div>

            <div v-if="hasSavingsAccount">
                <div class="p-8 bg-yellow-100 border border-yellow-300 rounded text-yellow-800 text-lg font-semibold mb-6">
                    Member already has a savings account.
                </div>
                <Link :href="'/members/'" class="inline-block">
                    <Button type="button" class="bg-blue-500 text-white hover:bg-blue-600">
                        Back to Member Page
                    </Button>
                </Link>
            </div>
            <form v-else @submit.prevent="handleSubmit" class="flex flex-col gap-6">
                <h2 class="text-lg font-semibold">Savings Account Information</h2>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="savings_account_number">Account Number</Label>
                        <Input id="savings_account_number" v-model="form.savings_account_number" type="text" required placeholder="Account Number" />
                        <InputError :message="form.errors.savings_account_number" />
                    </div>
                    <div class="flex-1">
                        <Label for="initial_deposit">Initial Deposit</Label>
                        <Input id="initial_deposit" v-model="form.initial_deposit" type="number" step="0.01" required placeholder="Initial Deposit" />
                        <InputError :message="form.errors.initial_deposit" />
                    </div>
                </div>

                <!-- Nominee Section -->
                <div class="border rounded-lg p-4 bg-gray-50 mt-4">
                    <h3 class="text-md font-semibold mb-4">Nominee Information</h3>
                    <div class="mt-4">
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="nominee_name">Nominee Name</Label>
                                <Input id="nominee_name" v-model="form.nominee_name" type="text" placeholder="Nominee Name" />
                                <InputError :message="form.errors.nominee_name" />
                            </div>
                            <div class="flex-1">
                                <Label for="relationship">Relationship</Label>
                                <select id="relationship" v-model="form.relationship" class="w-full border rounded p-2">
                                    <option value="" disabled>Select Relationship</option>
                                    <option v-for="option in props.relationshipOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>
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
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="address">Address</Label>
                                <Input id="address" v-model="form.address" type="text" placeholder="Address" />
                                <InputError :message="form.errors.address" />
                            </div>
                            <div class="flex-1">
                                <Label for="scan_image">Scan Image</Label>
                                <Input id="scan_image" type="file"
                                    @change="(e: Event) => form.scan_image = (e.target as HTMLInputElement).files?.[0] || null" />
                                <InputError :message="form.errors.scan_image" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Nominee Section -->

                <div class="mt-6 flex justify-end">
                    <Link href="/savings-accounts" class="mr-4">
                        <Button variant="destructive" type="button">Cancel</Button>
                    </Link>
                    <Button variant="submit" :disabled="form.processing">Create Savings Account</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
