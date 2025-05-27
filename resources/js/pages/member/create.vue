<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { defineProps } from 'vue';
import { ref, reactive, watch } from 'vue';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import Dropdown from '@/components/ui/Dropdown.vue';
import axios from 'axios';

const props = defineProps<{
    countries: { id: number, name: string }[];
    divisions: { id: number, name: string }[];
}>();

const dateValue = ref([]);
const formatter = ref({
    date: "DD MMM YYYY",
    month: "MMM",
});

const genderList = [
    { value: 'Male', label: 'Male' },
    { value: 'Female', label: 'Female' },
    { value: 'Others', label: 'Others' }
];

const religionList = [
    { value: 'Islam', label: 'Islam' },
    { value: 'Hindu', label: 'Hindu' },
    { value: 'Christian', label: 'Christian' },
    { value: 'Buddhist', label: 'Buddhist' },
    { value: 'Others', label: 'Others' },
];

const bloodGroupList = [
    { value: 'A+', label: 'A+' },
    { value: 'A-', label: 'A-' },
    { value: 'B+', label: 'B+' },
    { value: 'B-', label: 'B-' },
    { value: 'AB+', label: 'AB+' },
    { value: 'AB-', label: 'AB-' },
    { value: 'O+', label: 'O+' },
    { value: 'O-', label: 'O-' },
];

const maritalStatusList = [
    { value: 'Single', label: 'Single' },
    { value: 'Married', label: 'Married' },
    { value: 'Divorced', label: 'Divorced' },
    { value: 'Widowed', label: 'Widowed' },
];

const relationshipList = [
    { value: 'Father', label: 'Father' },
    { value: 'Mother', label: 'Mother' },
    { value: 'Spouse', label: 'Spouse' },
    { value: 'Sibling', label: 'Sibling' },
    { value: 'Child', label: 'Child' },
    { value: 'Other', label: 'Other' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Add Member',
        href: '/members/create',
    },
];

const form = useForm({
    name: '',
    father_name: '',
    mother_name: '',
    mobile: '',
    email: '',
    dob: '',
    place_of_birth: '',
    gender: '',
    nationality: props.countries.find(c => c.name === 'Bangladesh')?.id || '', // Set Bangladesh as default
    nid: '',

    religion: 'Christian', // Set Christian as default value
    blood_group: '',
    marital_status: '',

    occupation: '',
    educational_level: '',

    pre_address: '',
    pre_division: '',
    pre_district: '',
    pre_thana: '',
    pre_post_code: '',

    per_address: '',
    per_division: '',
    per_district: '',
    per_thana: '',
    per_post_code: '',

    nominee_name: '',
    nominee_nid: '',
    nominee_relationship: '',
    nominee_age: '',
    contact_number: '',
    nominee_address: '',

    introducer_name: '',
    introducer_account_number: '',
    introducer_signature: null as File | null,
    introducer_date: '',

    acknowledgement: false,
    previous_member_number: '',
    profile_image: null as File | null,
    signature_image: null as File | null,
    is_previous_member: false, // Add this field

});

const is_previous_member = ref(false);

const pre_districts = reactive<{ id: number; name: string }[]>([]);
const pre_thanas = reactive<{ id: number; name: string }[]>([]);
const per_districts = reactive<{ id: number; name: string }[]>([]);
const per_thanas = reactive<{ id: number; name: string }[]>([]);

watch(() => form.pre_division, async (newDivision) => {
    if (newDivision) {
        try {
            const response = await axios.get(route('api.divisions.districts', { division: newDivision }));
            pre_districts.splice(0, pre_districts.length, ...response.data);
        } catch (error) {
            console.error('Failed to fetch districts:', error);
        }
    } else {
        pre_districts.splice(0, pre_districts.length); // Clear districts if no division is selected
    }
});

watch(() => form.pre_district, async (newDistrict) => {
    if (newDistrict) {
        try {
            const response = await axios.get(route('api.districts.thanas', { district: newDistrict }));
            pre_thanas.splice(0, pre_thanas.length, ...response.data);
        } catch (error) {
            console.error('Failed to fetch thanas:', error);
        }
    } else {
        pre_thanas.splice(0, pre_thanas.length); // Clear thanas if no district is selected
    }
});

watch(() => form.per_division, async (newDivision) => {
    if (newDivision) {
        try {
            const response = await axios.get(route('api.divisions.districts', { division: newDivision }));
            per_districts.splice(0, per_districts.length, ...response.data);
        } catch (error) {
            console.error('Failed to fetch districts:', error);
        }
    } else {
        per_districts.splice(0, per_districts.length); // Clear districts if no division is selected
    }
});

watch(() => form.per_district, async (newDistrict) => {
    if (newDistrict) {
        try {
            const response = await axios.get(route('api.districts.thanas', { district: newDistrict }));
            per_thanas.splice(0, per_thanas.length, ...response.data);
        } catch (error) {
            console.error('Failed to fetch thanas:', error);
        }
    } else {
        per_thanas.splice(0, per_thanas.length); // Clear thanas if no district is selected
    }
});

const formatDateForDisplay = (date: string): string => {
    if (!date || !date.includes('-')) return '';
    const [year, month, day] = date.split('-');
    return `${day}/${month}/${year}`;
};

const formatDateForInput = (formattedDate: string): string => {
    if (!formattedDate) return '';
    const [day, month, year] = formattedDate.split('/');
    return `${year}-${month}-${day}`;
};

const submit = () => {
    form.post(route('member.add'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            alert('Member added successfully!');
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
            alert('Please fix the errors in the form.');
        },
    });
};

const sameAsPresent = ref(false);

watch(sameAsPresent, (checked) => {
    if (checked) {
        form.per_address = form.pre_address;
        form.per_division = form.pre_division;
        form.per_district = form.pre_district;
        form.per_thana = form.pre_thana;
        form.per_post_code = form.pre_post_code;
    }
});

watch(
    () => [form.pre_address, form.pre_division, form.pre_district, form.pre_thana, form.pre_post_code],
    ([pre_address, pre_division, pre_district, pre_thana, pre_post_code]) => {
        if (sameAsPresent.value) {
            form.per_address = pre_address;
            form.per_division = pre_division;
            form.per_district = pre_district;
            form.per_thana = pre_thana;
            form.per_post_code = pre_post_code;
        }
    }
);
</script>

<template>

    <Head title="Add Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <h2 class="text-lg font-semibold">Member Information</h2>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                            v-model="form.name" placeholder="Full name" />
                        <InputError :message="form.errors.name" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="father_name">Father's Name</Label>
                        <Input id="father_name" type="text" required autofocus :tabindex="2" v-model="form.father_name"
                            placeholder="Father's name" />
                        <InputError :message="form.errors.father_name" />
                    </div>
                    <div class="flex-1">
                        <Label for="mother_name">Mother's Name</Label>
                        <Input id="mother_name" type="text" required autofocus :tabindex="3" v-model="form.mother_name"
                            placeholder="Mother's name" />
                        <InputError :message="form.errors.mother_name" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="mobile">Mobile Number</Label>
                        <Input id="mobile" type="text" required autofocus :tabindex="4" autocomplete="contact"
                            v-model="form.mobile" placeholder="Mobile number" />
                        <InputError :message="form.errors.mobile" />
                    </div>
                    <div class="flex-1">
                        <Label for="email">Email</Label>
                        <Input id="email" type="text" required autofocus :tabindex="5" autocomplete="contact"
                            v-model="form.email" placeholder="Email" />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="dob">Date of Birth</Label>
                        <Input id="dob" type="date" required autofocus :tabindex="6" v-model="form.dob"
                            @input="form.dob = $event.target.value" :value="form.dob" placeholder="dd/mm/yyyy" />
                        <InputError :message="form.errors.dob" />
                    </div>
                    <div class="flex-1">
                        <Label for="place_of_birth">Place of Birth</Label>
                        <Input id="place_of_birth" type="text" autofocus :tabindex="7"
                            v-model="form.place_of_birth" placeholder="Place of Birth" />
                        <InputError :message="form.errors.place_of_birth" />
                    </div>

                    <div class="flex-1">
                        <Dropdown id="gender" label="Gender" :options="genderList" v-model="form.gender"
                            placeholder="Select Gender" :error="form.errors.gender" :tabindex="8" />
                    </div>

                </div>
                 <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="religion">Religion</Label>
                        <select id="religion"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.religion" :tabindex="9">
                            <option disabled value="">Select Religion</option>
                            <option v-for="religion in religionList" :key="religion.value" :value="religion.value">{{
                                religion.label }}</option>
                        </select>
                        <InputError :message="form.errors.religion" />
                    </div>
                    <div class="flex-1">
                        <Label for="blood_group">Blood Group</Label>
                        <select id="blood_group"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.blood_group" :tabindex="10">
                            <option disabled value="">Select Blood Group</option>
                            <option v-for="bloodGroup in bloodGroupList" :key="bloodGroup.value"
                                :value="bloodGroup.value">{{ bloodGroup.label }}</option>
                        </select>
                        <InputError :message="form.errors.blood_group" />
                    </div>
                    <div class="flex-1">
                        <Label for="marital_status">Marital Status</Label>
                        <select id="marital_status"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.marital_status" :tabindex="11">
                            <option disabled value="">Select Marital Status</option>
                            <option v-for="status in maritalStatusList" :key="status.value" :value="status.value">{{
                                status.label }}</option>
                        </select>
                        <InputError :message="form.errors.marital_status" />
                    </div>
                </div> 
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="nationality">Nationality</Label>
                        <select id="nationality"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.nationality" placeholder="Nationality" :tabindex="12">
                            <option disabled value="">Select Nationality</option>
                            <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name
                                }}</option>
                        </select>
                        <InputError :message="form.errors.nationality" />
                    </div>
                    <div class="flex-1">
                        <Label for="nid">National ID Number</Label>
                        <Input id="nid" type="text" required autofocus :tabindex="13" v-model="form.nid"
                            placeholder="National ID Number" />
                        <InputError :message="form.errors.nid" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="occupation">Occupation</Label>
                        <Input id="occupation" type="text" autofocus :tabindex="14" v-model="form.occupation"
                            placeholder="Occupation" />
                        <InputError :message="form.errors.occupation" />
                    </div>
                    <div class="flex-1">
                        <Label for="educational_level">Educational Level</Label>
                        <Input id="educational_level" type="text" autofocus :tabindex="15"
                            v-model="form.educational_level" placeholder="Educational Level" />
                        <InputError :message="form.errors.educational_level" />
                    </div>
                </div>

                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="pre_address">Present Address</Label>
                        <Input id="pre_address" type="text" required autofocus :tabindex="16" autocomplete="pre_address"
                            v-model="form.pre_address" placeholder="Address" />
                        <InputError :message="form.errors.pre_address" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="pre_division">Division</Label>
                        <select id="pre_division"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.pre_division" :tabindex="17">
                            <option disabled value="">Select Division</option>
                            <option v-for="division in divisions" :key="division.id" :value="division.id">{{
                                division.name }}</option>
                        </select>
                        <InputError :message="form.errors.pre_division" />
                    </div>
                    <div class="flex-1">
                        <Label for="pre_district">District</Label>
                        <select id="pre_district"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.pre_district" :tabindex="18">
                            <option disabled value="">Select District</option>
                            <option v-for="district in pre_districts" :key="district.id" :value="district.id">{{
                                district.name }}</option>
                        </select>
                        <InputError :message="form.errors.pre_district" />
                    </div>

                    <div class="flex-1">
                        <Label for="pre_thana">Thana</Label>
                        <select id="pre_thana"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.pre_thana" :tabindex="19">
                            <option disabled value="">Select Thana</option>
                            <option v-for="thana in pre_thanas" :key="thana.id" :value="thana.id">{{ thana.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.pre_thana" />
                    </div>
                    <div class="flex-1">
                        <Label for="pre_post_code">Post Code</Label>
                        <Input id="pre_post_code" type="text" required autofocus :tabindex="20"
                            v-model="form.pre_post_code" placeholder="Post Code" />
                        <InputError :message="form.errors.pre_post_code" />
                    </div>
                </div>

                <!-- Before Permanent Address -->
                <div class="flex items-center mb-2">
                    <input
                        id="same_as_present"
                        type="checkbox"
                        v-model="sameAsPresent"
                        class="mr-2"
                    />
                    <Label for="same_as_present">Same as Present address</Label>
                </div>

                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="per_address">Permanent Address</Label>
                        <Input id="per_address" type="text" required autofocus :tabindex="21" autocomplete="per_address"
                            v-model="form.per_address" placeholder="Address" :disabled="sameAsPresent" />
                        <InputError :message="form.errors.per_address" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="per_division">Division</Label>
                        <select id="per_division"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.per_division" :tabindex="22">
                            <option disabled value="">Select Division</option>
                            <option v-for="division in divisions" :key="division.id" :value="division.id">{{
                                division.name }}</option>
                        </select>
                        <InputError :message="form.errors.per_division" />
                    </div>
                    <div class="flex-1">
                        <Label for="per_district">District</Label>
                        <select id="per_district"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.per_district" :tabindex="23">
                            <option disabled value="">Select District</option>
                            <option v-for="district in per_districts" :key="district.id" :value="district.id">{{
                                district.name }}</option>
                        </select>
                        <InputError :message="form.errors.per_district" />
                    </div>

                    <div class="flex-1">
                        <Label for="per_thana">Thana</Label>
                        <select id="per_thana"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            v-model="form.per_thana" :tabindex="24">
                            <option disabled value="">Select Thana</option>
                            <option v-for="thana in per_thanas" :key="thana.id" :value="thana.id">{{ thana.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.per_thana" />
                    </div>
                    <div class="flex-1">
                        <Label for="per_post_code">Post Code</Label>
                        <Input id="per_post_code" type="text" required autofocus :tabindex="25"
                            v-model="form.per_post_code" placeholder="Post Code" />
                        <InputError :message="form.errors.per_post_code" />
                    </div>
                </div>

                <div class="grid gap-6 mt-10">
                    <div class="flex items-start">
                        <input id="is_previous_member" type="checkbox" v-model="form.is_previous_member" class="mr-2 mt-1"
                            :tabindex="26" />
                        <Label for="is_previous_member">
                            Were you previously a member of this co-operative society?
                        </Label>
                    </div>
                    <div v-if="form.is_previous_member" class="grid gap-6 mt-4">
                        <div class="grid gap-2">
                            <Label for="previous_member_number">Previous Member Number</Label>
                            <Input id="previous_member_number" type="text" v-model="form.previous_member_number"
                                placeholder="Enter Previous Member Number" :tabindex="27" />
                            <InputError :message="form.errors.previous_member_number" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 mt-10">
                    <h2 class="text-lg font-semibold">Upload Images</h2>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="profile_image">Profile Image</Label>
                            <Input id="profile_image" type="file" accept="image/*"
                                @change="(e: Event) => form.profile_image = (e.target as HTMLInputElement).files?.[0] || null" :tabindex="28" />
                            <InputError :message="form.errors.profile_image" />
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="signature_image">Signature Image</Label>
                            <Input id="signature_image" type="file" accept="image/*" required
                                @change="(e: Event) => form.signature_image = (e.target as HTMLInputElement).files?.[0] || null" :tabindex="29" />
                            <InputError :message="form.errors.signature_image" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 mt-10">
                    <h2 class="text-lg font-semibold">Nominee Information</h2>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="nominee_name">Nominee Name</Label>
                            <Input id="nominee_name" type="text" required autofocus :tabindex="30"
                                v-model="form.nominee_name" placeholder="Nominee Name" />
                            <InputError :message="form.errors.nominee_name" />
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="nominee_nid">NID/Birth Certificate Number</Label>
                            <Input id="nominee_nid" type="text" required autofocus :tabindex="31"
                                v-model="form.nominee_nid" placeholder="NID/Birth Certificate Number" />
                            <InputError :message="form.errors.nominee_nid" />
                        </div>
                        <div class="flex-1">
                            <Label for="nominee_relationship">Relationship</Label>
                            <select id="nominee_relationship"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                v-model="form.nominee_relationship" :tabindex="32">
                                <option disabled value="">Select Relationship</option>
                                <option v-for="relationship in relationshipList" :key="relationship.value"
                                    :value="relationship.value">{{ relationship.label }}</option>
                            </select>
                            <InputError :message="form.errors.nominee_relationship" />
                        </div>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="nominee_age">Age</Label>
                            <Input id="nominee_age" type="number" autofocus :tabindex="33"
                                v-model="form.nominee_age" placeholder="Nominee Age" />
                            <InputError :message="form.errors.nominee_age" />
                        </div>
                        <div class="flex-1">
                            <Label for="contact_number">Contact Number</Label>
                            <Input id="contact_number" type="text" required autofocus :tabindex="34"
                                v-model="form.contact_number" placeholder="Nominee Contact Number" />
                            <InputError :message="form.errors.contact_number" />
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="nominee_address">Nominee Address</Label>
                            <Input id="nominee_address" type="text" required autofocus :tabindex="35"
                                v-model="form.nominee_address" placeholder="Nominee Address" />
                            <InputError :message="form.errors.nominee_address" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 mt-10">
                    <h2 class="text-lg font-semibold">Introducer Information</h2>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="introducer_account_number">Introducer Account Number</Label>
                            <Input id="introducer_account_number" type="text" required autofocus :tabindex="36"
                                v-model="form.introducer_account_number" placeholder="Account Number" />
                            <InputError :message="form.errors.introducer_account_number" />
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="introducer_name">Introducer Name</Label>
                            <Input id="introducer_name" type="text" required autofocus :tabindex="37"
                                v-model="form.introducer_name" placeholder="Introducer Name" />
                            <InputError :message="form.errors.introducer_name" />
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="introducer_signature">Signature</Label>
                            <Input id="introducer_signature" type="file" required autofocus :tabindex="38"
                                @change="(e: Event) => form.introducer_signature = (e.target as HTMLInputElement).files?.[0] || null" />
                            <InputError :message="form.errors.introducer_signature" />
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="introducer_date">Date</Label>
                            <Input id="introducer_date" type="date" autofocus :tabindex="39"
                                v-model="form.introducer_date" />
                            <InputError :message="form.errors.introducer_date" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 mt-10">
                    <div class="flex items-start">
                        <input id="acknowledgement" type="checkbox" v-model="form.acknowledgement" required
                            class="mr-2 mt-1" :tabindex="40" />
                        <Label for="acknowledgement">
                            I/We hereby declare that the above information is true and correct to the best of my/our
                            knowledge.
                        </Label>
                    </div>
                    <InputError :message="form.errors.acknowledgement" />
                </div>

                <div class="flex justify-start mt-6">
                    <Button type="submit" class="bg-blue-500 text-white hover:bg-blue-600">
                        Submit
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>