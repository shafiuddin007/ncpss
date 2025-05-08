<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref, reactive, watch } from 'vue';
import Dropdown from '@/components/ui/Dropdown.vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Member',
        href: '/members',
    },
    {
        title: 'Edit',
        href: '',
    },
];

const props = defineProps({
    member: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.member.name,
    father_name: props.member.father_name,
    mother_name: props.member.mother_name,
    mobile: props.member.mobile,
    email: props.member.email,
    dob: props.member.dob,
    place_of_birth: props.member.place_of_birth,
    gender: props.member.gender,
    nationality: props.member.nationality,
    nid: props.member.nid,
    religion: props.member.religion,
    blood_group: props.member.blood_group,
    marital_status: props.member.marital_status,
    occupation: props.member.occupation,
    educational_level: props.member.educational_level,
    pre_address: props.member.pre_address,
    pre_division: props.member.pre_division,
    pre_district: props.member.pre_district,
    pre_thana: props.member.pre_thana,
    pre_post_code: props.member.pre_post_code,
    per_address: props.member.per_address,
    per_division: props.member.per_division,
    per_district: props.member.per_district,
    per_thana: props.member.per_thana,
    per_post_code: props.member.per_post_code,
    nominee_name: props.member.nominee_name,
    nominee_nid: props.member.nominee_nid,
    nominee_relationship: props.member.nominee_relationship,
    nominee_age: props.member.nominee_age,
    contact_number: props.member.contact_number,
    nominee_address: props.member.nominee_address,
    introducer_name: props.member.introducer_name,
    introducer_account_number: props.member.introducer_account_number,
    introducer_signature: null,
    introducer_date: props.member.introducer_date,
    acknowledgement: props.member.acknowledgement,
    previous_member_number: props.member.previous_member_number,
    profile_image: null,
    signature_image: null,
    is_previous_member: props.member.is_previous_member,
});

const submit = () => {
    form.put(route('members.update', props.member.id), {
        onSuccess: () => {
            alert('Member updated successfully!');
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <Head title="Edit Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-10">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Edit Member</h1>
            <form @submit.prevent="submit" class="bg-white shadow-md rounded-lg p-6 space-y-6">
                <h2 class="text-lg font-semibold">Member Information</h2>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="father_name">Father's Name</Label>
                        <input
                            id="father_name"
                            type="text"
                            v-model="form.father_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.father_name" />
                    </div>
                    <div class="flex-1">
                        <Label for="mother_name">Mother's Name</Label>
                        <input
                            id="mother_name"
                            type="text"
                            v-model="form.mother_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.mother_name" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="mobile">Mobile Number</Label>
                        <input
                            id="mobile"
                            type="text"
                            v-model="form.mobile"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.mobile" />
                    </div>
                    <div class="flex-1">
                        <Label for="email">Email</Label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="dob">Date of Birth</Label>
                        <input
                            id="dob"
                            type="date"
                            v-model="form.dob"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.dob" />
                    </div>
                    <div class="flex-1">
                        <Label for="place_of_birth">Place of Birth</Label>
                        <input
                            id="place_of_birth"
                            type="text"
                            v-model="form.place_of_birth"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <InputError :message="form.errors.place_of_birth" />
                    </div>
                </div>
                <!-- Add other fields here in the same way as in create.vue -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>