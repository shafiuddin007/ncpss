<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { watch, reactive, ref, computed } from 'vue';

const props = defineProps<{
    member: any,
    products: Array<{
        id: number,
        name: string,
        min_balance?: string | number,
        max_loan_amount?: string | number,
        interest_rate?: string | number,
        loan_term_months?: string | number
    }>
}>();

// Define breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: `${props.member.name}`, href: `/members/${props.member.id}` },
    { title: `Sell Product`, href: '' },
];

// steps
const steps = [
    { title: 'Product Information', href: '#product-info' },
    { title: 'Income & Expense', href: '#income-expence' },
    { title: 'Loan Information', href: '#loan-info' },
    { title: 'Family Member', href: '#family-member' },
    { title: 'Submit', href: '#submit' },
];

const stepContents = [
    'Product Information',
    'Income & Expense',
    'Loan Information',
    'Family Member',
    'Submit',
];

const currentStep = ref(0);

// Vue way to handle step navigation
function prevStep() {
    if (currentStep.value > 0) {
        currentStep.value--;
    }
}

function nextStep() {
    if (currentStep.value < steps.length - 1) {
        currentStep.value++;
    } else {
        handleSubmit();
    }
}

const form = useForm({
    code_number: '',
    loan_number: '',
    member_id: props.member.id,
    product_id: '',
    interest_rate: '',
    min_balance: '',
    max_loan_amount: '',
    loan_term_months: '',
    office_address: '',
    occupation: '',
    designation: '',
    office_contact: '',
    self_income: '',
    family_income: '',
    total_income: '',
    rent: '',
    food_expense: '',
    education_expense: '',
    transport_expense: '',
    other_expense: '',
    total_expense: '',
    current_share_amount: '',
    before_share_amount: '',
    loan_amount: '',
    loan_type: '',
    loan_purpose: '',
    previous_loans: '',
    is_reg_paid: false,
    total_installment: '',
    first_installment: '',
    other_loan_amount: '',
    other_loan_installment: '',
    other_loan_remaining: '',
    loan_surety_id: '',
    surety_name: '',
    self_deposit_amout: '',
    start_date: '',
    end_date: '',
    status: 'active',
    is_urgent: false,
    urgent_fee: 1000,
    loan_surety_type: '',
    family_member: '',

    grantors: [] as Array<{
        member_id: string,
        deposit_amount: string,
        loan_amount: string,
        document: File | null,
    }>,
    family_members: [] as Array<{
        relation: string,
        member_id: string,
        current_deposit: string,
        current_loan: string,
        signeture: File | null,
    }>,
});

const editingGrantorIndex = ref<number | null>(null);
// Temporary state for new grantor input
const newGrantor = reactive({
    member_id: '',
    deposit_amount: '',
    loan_amount: '',
    document: null as File | null,
});

function addGrantor() {
    const grantorData = { ...newGrantor };

    if (editingGrantorIndex.value !== null) {
        form.grantors[editingGrantorIndex.value] = grantorData;
        editingGrantorIndex.value = null;
    } else {
        form.grantors.push(grantorData);
    }
    // Reset fields
    newGrantor.member_id = '';
    newGrantor.deposit_amount = '';
    newGrantor.loan_amount = '';
    newGrantor.document = null;
}

function clearGrantor() {
    newGrantor.member_id = '';
    newGrantor.deposit_amount = '';
    newGrantor.loan_amount = '';
    newGrantor.document = null;
}

function editGrantor(index: number) {
    const grantor = form.grantors[index];
    newGrantor.member_id = grantor.member_id;
    newGrantor.deposit_amount = grantor.deposit_amount;
    newGrantor.loan_amount = grantor.loan_amount;
    newGrantor.document = grantor.document;

    editingGrantorIndex.value = index;
}

function removeGrantor(index: number) {
    form.grantors.splice(index, 1);
    editingGrantorIndex.value = null;
}

// Family member section
const editingFamilyMemberIndex = ref<number | null>(null);
const newFamilyMember = reactive({
    name: '',
    relation: '',
    member_id: '',
    contact_no: '',
    current_deposit: '',
    current_loan: '',
    signeture: null as File | null,
});

function addFamilyMember() {
    const familyMemberData = { ...newFamilyMember };

    if (editingFamilyMemberIndex.value !== null) {
        form.family_members[editingFamilyMemberIndex.value] = familyMemberData;
        editingFamilyMemberIndex.value = null;
    } else {
        form.family_members.push(familyMemberData);
    }

    // Reset fields
    newFamilyMember.name = '';
    newFamilyMember.relation = '';
    newFamilyMember.member_id = '';
    newFamilyMember.contact_no = '';
    newFamilyMember.current_deposit = '';
    newFamilyMember.current_loan = '';
    newFamilyMember.signeture = null;
}

function clearFamilyMember() {
    newFamilyMember.name = '';
    newFamilyMember.relation = '';
    newFamilyMember.member_id = '';
    newFamilyMember.contact_no = '';
    newFamilyMember.current_deposit = '';
    newFamilyMember.current_loan = '';
    newFamilyMember.signeture = null;
}

function editFamilyMember(index: number) {
    const member = form.family_members[index];
    newFamilyMember.relation = member.relation;
    newFamilyMember.member_id = member.member_id;
    newFamilyMember.current_deposit = member.current_deposit;
    newFamilyMember.current_loan = member.current_loan;
    newFamilyMember.signeture = member.signeture;

    editingFamilyMemberIndex.value = index;
}

function removeFamilyMember(index: number) {
    form.family_members.splice(index, 1);
    editingFamilyMemberIndex.value = null;
}

function handleDocumentUpload(e: Event, isNewMember = true) {
    const target = e.target as HTMLInputElement;
    if (target && target.files) {
        if (isNewMember) {
            newFamilyMember.signeture = target.files[0];
        } else {
            // For editing existing members
            const index = editingFamilyMemberIndex.value;
            if (index !== null) {
                form.family_members[index].signeture = target.files[0];
            }
        }
    }
}

const handleSubmit = () => {
    form.post(`/members/${props.member.id}/sell-product`);
};

watch(
    () => form.product_id,
    (newProductId) => {
        const selected = props.products.find(p => p.id === Number(newProductId));
        if (selected) {
            form.min_balance = selected.min_balance !== null ? String(selected.min_balance) : '0';
            form.max_loan_amount = selected.max_loan_amount !== null ? String(selected.max_loan_amount) : '0';
            form.interest_rate = selected.interest_rate !== null ? String(selected.interest_rate) : '0';
            form.loan_term_months = selected.loan_term_months !== null ? String(selected.loan_term_months) : '0';
        } else {
            form.min_balance = '';
            form.max_loan_amount = '';
            form.interest_rate = '';
            form.loan_term_months = '';
        }
    }
);

watch(
    [() => form.self_income, () => form.family_income],
    ([self, family]) => {
        // Convert to numbers, default to 0 if empty or invalid
        const selfVal = parseFloat(self) || 0;
        const familyVal = parseFloat(family) || 0;
        form.total_income = (selfVal + familyVal).toFixed(2);
    }
);

watch(
    [
        () => form.rent,
        () => form.food_expense,
        () => form.education_expense,
        () => form.transport_expense,
        () => form.other_expense,
    ],
    ([rent, food, education, transport, other]) => {
        const rentVal = parseFloat(rent) || 0;
        const foodVal = parseFloat(food) || 0;
        const educationVal = parseFloat(education) || 0;
        const transportVal = parseFloat(transport) || 0;
        const otherVal = parseFloat(other) || 0;
        form.total_expense = (rentVal + foodVal + educationVal + transportVal + otherVal).toFixed(2);
    }
);
</script>

<template>

    <Head title="Sell Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
                <!-- Progress Indicator -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 space-y-4 md:space-y-0">
                    <div v-for="(step, index) in steps" :key="index" class="flex items-center">
                        <div :id="step.href.substring(1)"
                            class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full"
                            :class="currentStep === index ? 'bg-[#106C4F] text-white' : 'bg-gray-200 text-gray-600'">
                            {{ index + 1 }}
                        </div>
                        <div class="ml-4 text-sm md:text-base font-semibold"
                            :class="currentStep === index ? 'text-[#106C44F]' : 'text-gray-600'">
                            {{ step.title }}
                        </div>
                    </div>
                </div>

                <!-- Step 1: Product Information -->
                <div v-show="currentStep === 0" id="product-info"
                    class="step-content grid gap-6 shadow-md p-6 border rounded-lg bg-gray-100">
                    <h2 class="text-2xl font-semibold text-center">Product Information</h2>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="product_id">Select Product</Label>
                            <select id="product_id" v-model="form.product_id" class="w-full border rounded p-2">
                                <option value="" disabled>Select a product</option>
                                <option v-for="product in props.products" :key="product.id" :value="product.id">
                                    {{ product.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.product_id" />
                        </div>
                        <div class="flex-1">
                            <Label for="min_balance">Min Amount</Label>
                            <Input id="min_balance" v-model="form.min_balance" type="number" step="0.01" />
                            <InputError :message="form.errors.min_balance" />
                        </div>
                        <div class="flex-1">
                            <Label for="max_loan_amount">Max Amount</Label>
                            <Input id="max_loan_amount" v-model="form.max_loan_amount" type="number" step="0.01" />
                            <InputError :message="form.errors.max_loan_amount" />
                        </div>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="interest_rate">Interest Rate</Label>
                            <Input id="interest_rate" v-model="form.interest_rate" type="number" />
                            <InputError :message="form.errors.interest_rate" />
                        </div>
                        <div class="flex-1">
                            <Label for="loan_term_months">Loan Term (Months)</Label>
                            <Input id="loan_term_months" v-model="form.loan_term_months" type="number" />
                            <InputError :message="form.errors.loan_term_months" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Income & Expense -->
                <div v-show="currentStep === 1" id="income-expence"
                    class="step-content grid gap-6 shadow-md p-6 border rounded-lg bg-gray-100">
                    <h2 class="text-2xl font-semibold text-center">Income & Expence Information</h2>
                    <h2 class="text-lg font-semibold">Job Information</h2>
                    <div class="flex gap-6">
                        <div class="flex-1"><Label for="office_address">Office Address</Label><Input id="office_address"
                                v-model="form.office_address" />
                            <InputError :message="form.errors.office_address" />
                        </div>
                        <div class="flex-1"><Label for="occupation">Occupation</Label><Input id="occupation"
                                v-model="form.occupation" />
                            <InputError :message="form.errors.occupation" />
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="designation">Designation</Label>
                            <Input id="designation" v-model="form.designation" />
                            <InputError :message="form.errors.designation" />
                        </div>
                        <div class="flex-1">
                            <Label for="office_contact">Office Contact</Label>
                            <Input id="office_contact" v-model="form.office_contact" />
                            <InputError :message="form.errors.office_contact" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold">Income</h2>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="self_income">Self Income</Label>
                            <Input id="self_income" v-model="form.self_income" type="number" step="0.01" />
                            <InputError :message="form.errors.self_income" />
                        </div>
                        <div class="flex-1">
                            <Label for="family_income">Family Income</Label>
                            <Input id="family_income" v-model="form.family_income" type="number" step="0.01" />
                            <InputError :message="form.errors.family_income" />
                        </div>
                        <div class="flex-1">
                            <Label for="total_income">Total Income</Label>
                            <Input id="total_income" v-model="form.total_income" type="number" step="0.01" readonly />
                            <InputError :message="form.errors.total_income" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold">Expense</h2>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="rent">Rent</Label>
                            <Input id="rent" v-model="form.rent" type="number" step="0.01" />
                            <InputError :message="form.errors.rent" />
                        </div>
                        <div class="flex-1">
                            <Label for="food_expense">Food Expense</Label>
                            <Input id="food_expense" v-model="form.food_expense" type="number" step="0.01" />
                            <InputError :message="form.errors.food_expense" />
                        </div>
                        <div class="flex-1">
                            <Label for="education_expense">Education Expense</Label>
                            <Input id="education_expense" v-model="form.education_expense" type="number" step="0.01" />
                            <InputError :message="form.errors.education_expense" />
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="transport_expense">Transport Expense</Label>
                            <Input id="transport_expense" v-model="form.transport_expense" type="number" step="0.01" />
                            <InputError :message="form.errors.transport_expense" />
                        </div>
                        <div class="flex-1">
                            <Label for="other_expense">Other Expense</Label>
                            <Input id="other_expense" v-model="form.other_expense" type="number" step="0.01" />
                            <InputError :message="form.errors.other_expense" />
                        </div>
                        <div class="flex-1">
                            <Label for="total_expense">Total Expense</Label>
                            <Input id="total_expense" v-model="form.total_expense" type="number" step="0.01" readonly />
                            <InputError :message="form.errors.total_expense" />
                        </div>
                    </div>
                </div>

                <!-- Step 3: Loan Information -->
                <div v-show="currentStep === 2" id="loan-info"
                    class="step-content grid gap-6 shadow-md p-6 border rounded-lg bg-gray-100">
                    <h2 class="text-2xl font-semibold text-center">Loan Information</h2>

                    <div class="grid gap-6 mt-10">
                        <div class="flex gap-6">
                            <div class="relative cursor-pointer dark:text-white flex-1 ">
                                <span
                                    class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-indigo-500 rounded-lg dark:bg-gray-200"></span>
                                <div
                                    class="relative p-6 bg-teal-100 dark:bg-gray-800 border-2 border-indigo-500 dark:border-gray-300 rounded-lg hover:scale-105 transition duration-500">
                                    <div class="flex items-center">
                                        <h3 class="my-2 ml-3 text-lg font-bold text-gray-800 dark:text-white">Current
                                            Share
                                            Amount</h3>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        This is the short description of your feature.
                                    </p>
                                </div>
                            </div>

                            <div class="relative cursor-pointer dark:text-white flex-1">
                                <span
                                    class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-indigo-500 rounded-lg dark:bg-gray-200"></span>
                                <div
                                    class="relative p-6 bg-green-100 dark:bg-gray-800 border-2 border-indigo-500 dark:border-gray-300 rounded-lg hover:scale-105 transition duration-500">
                                    <div class="flex items-center">
                                        <h3 class="my-2 ml-3 text-lg font-bold text-gray-800 dark:text-white">3 Month
                                            Before
                                            Share Amount</h3>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        This is the short description of your feature.
                                    </p>
                                </div>
                            </div>

                            <div class="relative cursor-pointer dark:text-white flex-1">
                                <span
                                    class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-indigo-500 rounded-lg dark:bg-gray-200"></span>
                                <div
                                    class="relative p-6 bg-indigo-100 dark:bg-gray-800 border-2 border-indigo-500 dark:border-gray-300 rounded-lg hover:scale-105 transition duration-500">
                                    <div class="flex items-center">
                                        <h3 class="my-2 ml-3 text-lg font-bold text-gray-800 dark:text-white">Previous
                                            Loans
                                        </h3>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        This is the short description of your feature.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 mt-10">
                        <h2 class="text-lg font-semibold">Applied Loan Information</h2>
                        <div class="flex gap-6">
                            <div class="flex-1">
                                <Label for="loan_amount">Loan Amount</Label>
                                <Input id="loan_amount" v-model="form.loan_amount" type="number" step="0.01" />
                                <InputError :message="form.errors.loan_amount" />
                            </div>

                            <div class="flex-1">
                                <Label for="loan_purpose">Loan Purpose</Label>
                                <Input id="loan_purpose" v-model="form.loan_purpose" />
                                <InputError :message="form.errors.loan_purpose" />
                            </div>
                        </div>

                        <Label for="loan_type">Loan Type</Label>
                        <div class="flex-1 border rounded-md p-4 w-full bg-white">

                            <div class="flex items-start">
                                <Label for="general"
                                    class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                    <input id="general" type="radio" value="General" v-model="form.loan_type"
                                        class="mr-2 mt-1" :tabindex="26" />
                                    <i class="pl-2">General</i>
                                </Label>
                                <Label for="is_urgent"
                                    class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                    <input id="is_urgent" type="radio" value="Urgent" v-model="form.loan_type"
                                        class="mr-2 mt-1" :tabindex="26" />
                                    <i class="pl-2"> Urgent</i>
                                </Label>
                                <Label for="top_up"
                                    class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                    <input id="top_up" type="radio" value="TopUp" v-model="form.loan_type"
                                        class="mr-2 mt-1" :tabindex="26" />
                                    <i class="pl-2">Top Up</i>
                                </Label>

                                <Label for="others"
                                    class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                    <input id="others" value="Others" type="radio" v-model="form.loan_type"
                                        class="mr-2 mt-1" :tabindex="26" />
                                    <i class="pl-2">Others</i>
                                </Label>
                            </div>
                            <InputError :message="form.errors.loan_type" />
                            <div v-if="form.loan_type === 'Urgent'" class="grid gap-6 mt-4 bg-gray-100 p-4 rounded-md">
                                <div class="grid gap-2">
                                    <Label for="urgent_fee">Urgent Fee Amount</Label>
                                    <Input id="urgent_fee" type="text" v-model="form.urgent_fee"
                                        placeholder="Urgent Fee Amount" :tabindex="27" />
                                    <InputError :message="form.errors.urgent_fee" />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-1">
                                <Label for="total_installment">Total Installment</Label>
                                <Input id="total_installment" v-model="form.total_installment" type="number" />
                                <InputError :message="form.errors.total_installment" />
                            </div>
                            <div class="flex-1">
                                <Label for="start_date">First Installment Start Date</Label>
                                <Input id="start_date" v-model="form.start_date" type="date" />
                                <InputError :message="form.errors.start_date" />
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 mt-10">
                        <h2 class="text-lg font-semibold">Other Loan Information</h2>
                        <div class="flex gap-6">
                            <div class="flex-1">
                                <Label for="other_loan_amount">Other Loan Amount</Label>
                                <Input id="other_loan_amount" v-model="form.other_loan_amount" type="number"
                                    step="0.01" />
                                <InputError :message="form.errors.other_loan_amount" />
                            </div>
                            <div class="flex-1">
                                <Label for="other_loan_installment">Other Loan Installment</Label>
                                <Input id="other_loan_installment" v-model="form.other_loan_installment" type="number"
                                    step="0.01" />
                                <InputError :message="form.errors.other_loan_installment" />
                            </div>
                            <div class="flex-1">
                                <Label for="other_loan_remaining">Other Loan Remaining</Label>
                                <Input id="other_loan_remaining" v-model="form.other_loan_remaining" type="number"
                                    step="0.01" />
                                <InputError :message="form.errors.other_loan_remaining" />
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 mt-10">
                        <h2 class="text-lg font-semibold">Details Of Loan Collateral</h2>
                        <div class="flex-1 border rounded-md p-4 w-full bg-white">
                            <div class="flex gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mt-2">
                                        <Label for="self_deposit"
                                            class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                            <input type="radio" id="self_deposit" value="self_deposit"
                                                v-model="form.loan_surety_type" />
                                            <i class="pl-2">Self Deposit</i>
                                        </Label>
                                        <Label for="grantor"
                                            class="mr-4 flex bg-gray-200 text-gray-700 rounded-md px-3 py-2 my-3  hover:bg-indigo-300 cursor-pointer">
                                            <input type="radio" id="grantor" value="grantor"
                                                v-model="form.loan_surety_type" />
                                            <i class="pl-2">Grantor</i>
                                        </Label>
                                    </div>
                                </div>
                            </div>
                            <div v-if="form.loan_surety_type === 'grantor'" class="mt-8 space-y-6">

                                <!-- Grantor Input Form -->
                                <div class="bg-gray-100 p-6 rounded-xl shadow-md border border-gray-200 space-y-4">
                                    <h2 class="text-xl font-semibold text-gray-800">Add Grantor</h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <Label for="grantor_member_id">Grantor Member ID</Label>
                                            <Input id="grantor_member_id" v-model="newGrantor.member_id"
                                                placeholder="Enter member ID" />
                                        </div>
                                        <div>
                                            <Label for="grantor_deposit_amount">NPCSS Deposite Amount</Label>
                                            <Input id="grantor_deposit_amount" v-model="newGrantor.deposit_amount"
                                                placeholder="Enter amount" />
                                        </div>
                                        <div>
                                            <Label for="grantor_loan_amount">NPCSS Loan Amount (if any)</Label>
                                            <Input id="grantor_loan_amount" v-model="newGrantor.loan_amount"
                                                placeholder="Enter amount" />
                                        </div>
                                        <div>
                                            <Label for="grantor_signeture">Grantor Signature</Label>
                                            <input id="grantor_signeture" type="file" accept="image/*,application/pdf"
                                                @change="e => {
                                                    const target = e.target as HTMLInputElement;
                                                    if (target && target.files) newGrantor.document = target.files[0];
                                                }"
                                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm p-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <Button type="button" variant="outline" @click="clearGrantor">
                                            Clear
                                        </Button>
                                        <Button type="button" @click="addGrantor">
                                            {{ editingGrantorIndex !== null ? 'Update Grantor' : 'Add Grantor' }}
                                        </Button>
                                    </div>
                                    <!-- Grantors Table -->
                                    <div v-if="form.grantors.length" class="mt-6">
                                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Grantor List</h3>
                                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                                                <thead class="bg-gray-200 text-xs uppercase text-gray-600">
                                                    <tr>
                                                        <th class="px-4 py-3 text-center">Member ID</th>
                                                        <th class="px-4 py-3 text-center">Deposit Amount</th>
                                                        <th class="px-4 py-3 text-center">Loan Amount</th>
                                                        <th class="px-4 py-3 text-center">Document</th>
                                                        <th class="px-4 py-3 text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    <tr v-for="(g, idx) in form.grantors" :key="idx"
                                                        class="hover:bg-gray-50">
                                                        <td class="px-4 py-2 text-center">{{ g.member_id }}</td>
                                                        <td class="px-4 py-2 text-center">{{ g.deposit_amount }}</td>
                                                        <td class="px-4 py-2 text-center">{{ g.loan_amount }}</td>
                                                        <td class="px-4 py-2 text-center">
                                                            <span v-if="g.document"
                                                                class="text-blue-600 underline cursor-pointer">
                                                                {{ g.document.name }}
                                                            </span>
                                                            <span v-else class="text-gray-400">-</span>
                                                        </td>
                                                        <td class="px-4 py-2 flex justify-center gap-2">
                                                            <Button type="button" variant="secondary" size="sm"
                                                                @click="editGrantor(idx)">
                                                                Edit
                                                            </Button>
                                                            <Button type="button" variant="destructive" size="sm"
                                                                @click="removeGrantor(idx)">
                                                                Delete
                                                            </Button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.loan_surety_type === 'self_deposit'"
                                class="flex gap-6 mt-4 bg-gray-100 p-4 rounded-md">
                                <div class="flex-1">
                                    <Label for="self_deposit_amout">Self Diposit Amount (NPCSS)</Label>
                                    <Input id="self_deposit_amout" v-model="form.self_deposit_amout" />
                                    <InputError :message="form.errors.self_deposit_amout" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Family Member -->
                <div v-show="currentStep === 3"
                    class="step-content grid gap-6 shadow-md p-6 border rounded-lg bg-gray-100">
                    <h2 class="text-2xl font-semibold text-center">Family Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="gap-6">
                            <Label for="family_member">Number of Family Members</Label>
                            <Input id="family_member" v-model="form.family_member"
                                placeholder="Number of Family Members" />
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800">Add Family Member (Who is member of NPCSS)
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <Label for="family_member_relation">Relation</Label>
                                <Input id="family_member_relation" v-model="newFamilyMember.relation"
                                    placeholder="E.g. Spouse, Child" />
                            </div>
                            <div>
                                <Label for="family_member_id">Member ID (if applicable)</Label>
                                <Input id="family_member_id" v-model="newFamilyMember.member_id"
                                    placeholder="Enter member ID" />
                            </div>
                            <div>
                                <Label for="family_member_deposit">NPCSS Current Deposit</Label>
                                <Input id="family_member_deposit" v-model="newFamilyMember.current_deposit"
                                    type="number" step="0.01" placeholder="Enter amount" />
                            </div>
                            <div>
                                <Label for="family_member_loan">NPCSS Current Loan (if any)</Label>
                                <Input id="family_member_loan" v-model="newFamilyMember.current_loan" type="number"
                                    step="0.01" placeholder="Enter amount" />
                            </div>
                            <div>
                                <Label for="family_member_document">Document (Photo or PDF)</Label>
                                <input id="family_member_document" type="file" accept="image/*,application/pdf"
                                    @change="handleDocumentUpload($event, true)"
                                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm p-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="mt-1 text-sm text-gray-500">Upload identification or supporting document
                                </p>
                                <div v-if="newFamilyMember.signeture" class="mt-2 text-sm text-blue-600">
                                    Selected: {{ newFamilyMember.signeture.name }}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button type="button" variant="outline" @click="clearFamilyMember">
                                Clear
                            </Button>
                            <Button type="button" @click="addFamilyMember">
                                {{ editingFamilyMemberIndex !== null ? 'Update Member' : 'Add Member' }}
                            </Button>
                        </div>

                        <!-- Family Members Table -->
                        <div v-if="form.family_members.length" class="mt-6">
                            <h3 class="text-lg font-semibold mb-3 text-gray-800">Family Member List</h3>
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 text-center">Relation</th>
                                            <th class="px-4 py-3 text-center">Member ID</th>
                                            <th class="px-4 py-3 text-center">Deposit</th>
                                            <th class="px-4 py-3 text-center">Loan</th>
                                            <th class="px-4 py-3 text-center">Document</th>
                                            <th class="px-4 py-3 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <tr v-for="(member, idx) in form.family_members" :key="idx"
                                            class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-center">{{ member.relation }}</td>
                                            <td class="px-4 py-2 text-center">{{ member.member_id || '-' }}</td>
                                            <td class="px-4 py-2 text-center">{{ member.current_deposit }}</td>
                                            <td class="px-4 py-2 text-center">{{ member.current_loan || '-' }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <span v-if="member.signeture"
                                                    class="text-blue-600 underline cursor-pointer">
                                                    {{ member.signeture.name }}
                                                </span>
                                                <span v-else class="text-gray-400">-</span>
                                            </td>
                                            <td class="px-4 py-2 flex justify-center gap-2">
                                                <Button type="button" variant="secondary" size="sm"
                                                    @click="editFamilyMember(idx)">
                                                    Edit
                                                </Button>
                                                <Button type="button" variant="destructive" size="sm"
                                                    @click="removeFamilyMember(idx)">
                                                    Delete
                                                </Button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Submit -->
                <div v-show="currentStep === 4" class="grid gap-6 shadow-md p-6 border rounded-lg bg-gray-100">
                    <h2 class="text-2xl font-semibold text-center">Review and Submit</h2>
                    <h3 class="text-xl font-semibold mb-4">Please review all the information before submitting</h3>
                    <div class="flex flex-col gap-6 mt-10">

                        <!-- Product Information Card -->
                      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <!-- Card Header -->
    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 mr-3">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <div>
            <h4 class="font-semibold text-lg text-gray-800">Product Information</h4>
            <p class="text-sm text-gray-500">Loan product details</p>
        </div>
    </div>

    <!-- Product Selection -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
            <h5 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Selected Product</h5>
            <span class="px-3 py-1 text-xs font-medium rounded-full" 
                  :class="form.product_id ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                {{ form.product_id ? 'Selected' : 'Not selected' }}
            </span>
        </div>
        <div class="bg-white p-4 rounded-md border border-gray-200 shadow-xs">
            <p class="text-gray-800 font-medium text-center text-lg">
                {{form.product_id && props.products ? (props.products.find(p => p.id === Number(form.product_id))?.name) || 'Product not found' : 'No product selected'}}
            </p>
        </div>
    </div>

    <!-- Key Features -->
    <div class="space-y-4">
        <h5 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Loan Terms</h5>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-xs transition-shadow">
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Minimum Amount</p>
                    <p class="text-lg font-semibold text-blue-600">{{ form.min_balance || '0' }} BDT</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-xs transition-shadow">
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Maximum Amount</p>
                    <p class="text-lg font-semibold text-blue-600">{{ form.max_loan_amount || '0' }} BDT</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-xs transition-shadow">
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Interest Rate</p>
                    <p class="text-lg font-semibold text-purple-600">{{ form.interest_rate || '0' }}%</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-xs transition-shadow">
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Loan Term</p>
                    <p class="text-lg font-semibold text-gray-800">{{ form.loan_term_months || '0' }} months</p>
                </div>
            </div>
        </div>
    </div>
</div>

                        <!-- Income & Expense -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mt-6">
                            <!-- Card Header -->
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2m0 0c1.1 0 2 .9 2 2s-.9 2-2 2m0-6V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-lg text-gray-800">Income & Expense</h4>
                                    <p class="text-sm text-gray-500">Financial overview</p>
                                </div>
                            </div>

                            <!-- Professional Information - Full Width -->
                            <div class="mb-6">
                                <div class="bg-indigo-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-blue-600 mb-3 uppercase tracking-wider">
                                        Professional Information
                                    </h5>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Office Address</span>
                                            <span class="text-gray-800 font-medium text-right">{{
                                                form.office_address || 'Not provided' }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Occupation</span>
                                            <span class="text-gray-800 font-medium">{{ form.occupation || 'N/A'
                                                }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Designation</span>
                                            <span class="text-gray-800 font-medium">{{ form.designation || 'N/A'
                                                }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Office Contact</span>
                                            <span class="text-gray-800 font-medium">{{ form.office_contact || 'N/A'
                                                }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Details - Two Columns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Income Section -->
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-blue-600 mb-3 uppercase tracking-wider">
                                        Income
                                    </h5>
                                    <div class="space-y-3">
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Self Income</span>
                                            <span class="text-green-700 font-medium">{{ form.self_income || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Family Income</span>
                                            <span class="text-green-700 font-medium">{{ form.family_income || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-blue-100 mt-2">
                                            <span class="text-gray-700 font-medium">Total Income</span>
                                            <span class="text-blue-700 font-semibold">{{ form.total_income || '0' }}
                                                BDT</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expense Section -->
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-red-600 mb-3 uppercase tracking-wider">
                                        Expenses
                                    </h5>
                                    <div class="space-y-3">
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Rent</span>
                                            <span class="text-red-700 font-medium">{{ form.rent || '0' }} BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Food</span>
                                            <span class="text-red-700 font-medium">{{ form.food_expense || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Education</span>
                                            <span class="text-red-700 font-medium">{{ form.education_expense || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Transport</span>
                                            <span class="text-red-700 font-medium">{{ form.transport_expense || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                            <span class="text-gray-600">Other</span>
                                            <span class="text-red-700 font-medium">{{ form.other_expense || '0' }}
                                                BDT</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-red-100 mt-2">
                                            <span class="text-gray-700 font-medium">Total Expense</span>
                                            <span class="text-purple-700 font-semibold">{{ form.total_expense || '0' }}
                                                BDT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Loan Details Card -->
                    <!-- Loan Information Card - Professional Version -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mt-6">
                        <!-- Card Header -->
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Loan Information</h4>
                                <p class="text-sm text-gray-500">Application details</p>
                            </div>
                        </div>

                        <!-- Content Grid -->
                        <div class="space-y-6">
                            <!-- Applied Loan Section -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h5 class="text-sm font-medium text-blue-600 mb-3 uppercase tracking-wider">Applied Loan
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Loan Amount</span>
                                        <span class="text-blue-700 font-medium">{{ form.loan_amount || '0' }} BDT</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Loan Purpose</span>
                                        <span class="text-gray-800 font-medium">{{ form.loan_purpose || 'Not specified'
                                            }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Loan Type</span>
                                        <span class="font-medium" :class="{
                                            'text-blue-600': form.loan_type === 'General',
                                            'text-orange-600': form.loan_type === 'Urgent',
                                            'text-purple-600': form.loan_type === 'TopUp',
                                            'text-gray-600': form.loan_type === 'Others'
                                        }">
                                            {{ form.loan_type || 'Not specified' }}
                                            <span v-if="form.loan_type === 'Urgent'"
                                                class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">
                                                +{{ form.urgent_fee || '0' }} BDT fee
                                            </span>
                                        </span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Total Installments</span>
                                        <span class="text-gray-800 font-medium">{{ form.total_installment || '0'
                                            }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Start Date</span>
                                        <span class="text-gray-800 font-medium">{{ form.start_date || 'Not set'
                                            }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Loans Section -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="text-sm font-medium text-gray-600 mb-3 uppercase tracking-wider">Other Loan
                                    Information</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Loan Amount</span>
                                        <span class="text-blue-700 font-medium">{{ form.other_loan_amount || '0' }}
                                            BDT</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Installment</span>
                                        <span class="text-gray-800 font-medium">{{ form.other_loan_installment || '0' }}
                                        </span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white px-3 py-2 rounded-md border border-gray-200">
                                        <span class="text-gray-600">Remaining</span>
                                        <span class="text-gray-800 font-medium">{{ form.other_loan_remaining || '0' }}
                                            BDT</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Collateral Section -->
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h5 class="text-sm font-medium text-purple-600 mb-3 uppercase tracking-wider">Loan
                                    Collateral</h5>

                                <div v-if="form.loan_surety_type === 'self_deposit'"
                                    class="bg-white p-3 rounded-md border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Self Deposit Amount</span>
                                        <span class="text-purple-700 font-medium">{{ form.self_deposit_amout || '0' }}
                                            BDT</span>
                                    </div>
                                </div>

                                <div v-if="form.loan_surety_type === 'grantor' && form.grantors.length" class="mt-3">
                                    <h6 class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wider">Grantors
                                        ({{ form.grantors.length }})</h6>
                                    <div class="space-y-2">
                                        <div v-for="(grantor, index) in form.grantors" :key="index"
                                            class="bg-white p-3 rounded-md border border-gray-200">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                <div>
                                                    <p class="text-xs text-gray-500">Member ID</p>
                                                    <p class="font-medium">{{ grantor.member_id || '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Deposit Amount</p>
                                                    <p class="font-medium text-blue-600">{{ grantor.deposit_amount ||
                                                        '0' }} BDT</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Loan Amount</p>
                                                    <p class="font-medium text-purple-600">{{ grantor.loan_amount || '0'
                                                        }} BDT</p>
                                                </div>
                                            </div>
                                            <div v-if="grantor.document" class="mt-2">
                                                <p class="text-xs text-gray-500">Document</p>
                                                <p class="text-sm text-blue-600 underline">{{ grantor.document.name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="!form.loan_surety_type"
                                    class="bg-white p-3 rounded-md border border-gray-200 text-center">
                                    <p class="text-gray-500">No collateral method selected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Family Information Card - Professional Version -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mt-6">
                        <!-- Card Header -->
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Family Information</h4>
                                <p class="text-sm text-gray-500">NPCSS member family details</p>
                            </div>
                        </div>

                        <!-- Family Summary -->
                        <div class="flex justify-between items-center bg-gray-50 px-4 py-3 rounded-lg mb-6">
                            <span class="text-gray-700 font-medium">Total Family Members</span>
                            <span class="text-lg font-semibold text-indigo-600">{{ form.family_member || '0' }}</span>
                        </div>

                        <!-- Family Members Section -->
                        <div v-if="form.family_members.length">
                            <h5 class="text-sm font-medium text-gray-600 mb-3 uppercase tracking-wider">NPCSS Member
                                Family ({{ form.family_members.length }})</h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="(member, index) in form.family_members" :key="index"
                                    class="bg-white p-4 rounded-lg border border-gray-200 shadow-xs hover:shadow-sm transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h6 class="font-medium text-gray-800 capitalize">{{ member.relation }}</h6>
                                            <p class="text-xs text-gray-500">Member ID: {{ member.member_id || 'Not provided' }}</p>
                                        </div>
                                        <span v-if="member.signeture"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Document
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mt-3">
                                        <div>
                                            <p class="text-xs text-gray-500">Current Deposit</p>
                                            <p class="text-sm font-medium text-green-600">{{ member.current_deposit ||
                                                '0' }} BDT</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Current Loan</p>
                                            <p class="text-sm font-medium text-red-600">{{ member.current_loan || '0' }}
                                                BDT</p>
                                        </div>
                                    </div>

                                    <div v-if="member.signeture" class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-1">Attached Document</p>
                                        <p class="text-sm text-blue-600 truncate">{{ member.signeture.name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="bg-gray-50 p-6 rounded-lg text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <h5 class="mt-2 text-sm font-medium text-gray-700">No family members added</h5>
                            <p class="mt-1 text-sm text-gray-500">Add NPCSS member family details if applicable</p>
                        </div>
                    </div>

                </div>
                <div class="flex justify-between mt-6">
                    <!-- Previous Button - shown on all steps except first -->
                    <Button v-if="currentStep > 0" type="button" variant="outline" @click="prevStep"
                        :class="{ 'opacity-50 cursor-not-allowed': currentStep === 0 }">
                        Previous
                    </Button>
                    <div v-else class="flex-1"></div> <!-- Spacer when no back button -->

                    <!-- Next/Submit Button -->
                    <Button v-if="currentStep < steps.length - 1" type="button" @click="nextStep">
                        Next
                    </Button>

                    <!-- Submit Button - only on last step -->
                    <Button v-else type="submit" :disabled="form.processing"
                        :class="{ 'opacity-70 cursor-not-allowed': form.processing }">
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>