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
import { now } from '@vueuse/core';

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

const hasEarningSource = ref(true);

const form = useForm({
    name: '',
    pin: '', // Add PIN field
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
    introducer_pin: '',
    introducer_signature: null as File | null,
    introducer_date: '',

    acknowledgement: false,
    previous_member_number: '',
    profile_image: null as File | null,
    signature_image: null as File | null,
    is_previous_member: false, // Add this field

    employer_name: '',
    employer_address: '',
    designation: '', // Add designation field
    employer_email: '',
    employer_phone: '',

    earning_source: true, // Set initial value directly, avoid referencing hasEarningSource before declaration
});

const is_previous_member = ref(false);


function toggleSwitch() {
    hasEarningSource.value = !hasEarningSource.value;
}

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

const introducerInfo = ref(null);
const introducerLoading = ref(false);
const introducerError = ref('');

async function checkIntroducer() {
    introducerLoading.value = true;
    introducerError.value = '';
    introducerInfo.value = null;
    try {
        const pin = form.introducer_pin;
        if (!pin) {
            introducerError.value = 'Please enter Introducer PIN.';
            introducerLoading.value = false;
            return;
        }
        const response = await axios.get(route('member.introducer-info', { pin }));
        introducerInfo.value = response.data;
        form.introducer_name = introducerInfo.value ? introducerInfo.value.name : '';
        form.introducer_account_number = introducerInfo.value ? String(introducerInfo.value.id) : ''; // <-- always set this

        console.log('Introducer Info:',  form.introducer_account_number);
        // Set introducer_date in 'YYYY-MM-DD' format for database using native JS
        const d = new Date();
        form.introducer_date = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
    } catch (err) {
        introducerError.value = err.response?.data?.message || 'Introducer not found.';
    } finally {
        introducerLoading.value = false;
    }
}

watch(hasEarningSource, (val) => {
    form.earning_source = val;
});


</script>

<template>

    <Head title="Add Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="border rounded-lg p-4 bg-gray-50 mt-4">
                    <h3 class="text-md font-semibold mb-4">Member Information</h3>
                    <div class="mt-4">
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="name">Name</Label>
                                <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                                    v-model="form.name" placeholder="Full name" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="flex-1">
                                <Label for="pin">PIN</Label>
                                <Input id="pin" type="text" v-model="form.pin" :tabindex="2" placeholder="PIN" />
                                <InputError :message="form.errors.pin" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="father_name">Father's Name</Label>
                                <Input id="father_name" type="text" required :tabindex="3" v-model="form.father_name"
                                    placeholder="Father's name" />
                                <InputError :message="form.errors.father_name" />
                            </div>
                            <div class="flex-1">
                                <Label for="mother_name">Mother's Name</Label>
                                <Input id="mother_name" type="text" required :tabindex="4" v-model="form.mother_name"
                                    placeholder="Mother's name" />
                                <InputError :message="form.errors.mother_name" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="mobile">Mobile Number</Label>
                                <Input id="mobile" type="text" required :tabindex="5" autocomplete="contact"
                                    v-model="form.mobile" placeholder="Mobile number" />
                                <InputError :message="form.errors.mobile" />
                            </div>
                            <div class="flex-1">
                                <Label for="email">Email</Label>
                                <Input id="email" type="text" :tabindex="6" autocomplete="contact" v-model="form.email"
                                    placeholder="Email" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="dob">Date of Birth</Label>
                                <Input id="dob" type="date" required :tabindex="7" v-model="form.dob"
                                    @input="form.dob = $event.target.value" :value="form.dob"
                                    placeholder="dd/mm/yyyy" />
                                <InputError :message="form.errors.dob" />
                            </div>
                            <div class="flex-1">
                                <Label for="place_of_birth">Place of Birth</Label>
                                <Input id="place_of_birth" type="text" :tabindex="8" v-model="form.place_of_birth"
                                    placeholder="Place of Birth" />
                                <InputError :message="form.errors.place_of_birth" />
                            </div>
                            <div class="flex-1">
                                <Dropdown id="gender" label="Gender" :options="genderList" v-model="form.gender"
                                    placeholder="Select Gender" :error="form.errors.gender" :tabindex="9" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="religion">Religion</Label>
                                <select id="religion"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    v-model="form.religion" :tabindex="10">
                                    <option disabled value="">Select Religion</option>
                                    <option v-for="religion in religionList" :key="religion.value"
                                        :value="religion.value">{{ religion.label }}</option>
                                </select>
                                <InputError :message="form.errors.religion" />
                            </div>
                            <div class="flex-1">
                                <Label for="blood_group">Blood Group</Label>
                                <select id="blood_group"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    v-model="form.blood_group" :tabindex="11">
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
                                    v-model="form.marital_status" :tabindex="12">
                                    <option disabled value="">Select Marital Status</option>
                                    <option v-for="status in maritalStatusList" :key="status.value"
                                        :value="status.value">{{ status.label }}</option>
                                </select>
                                <InputError :message="form.errors.marital_status" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="nationality">Nationality</Label>
                                <select id="nationality"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    v-model="form.nationality" placeholder="Nationality" :tabindex="13">
                                    <option disabled value="">Select Nationality</option>
                                    <option v-for="country in countries" :key="country.id" :value="country.id">{{
                                        country.name }}</option>
                                </select>
                                <InputError :message="form.errors.nationality" />
                            </div>
                            <div class="flex-1">
                                <Label for="nid">National ID Number</Label>
                                <Input id="nid" type="text" :tabindex="14" v-model="form.nid"
                                    placeholder="National ID Number" />
                                <InputError :message="form.errors.nid" />
                            </div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="occupation">Occupation</Label>
                                <Input id="occupation" type="text" :tabindex="15" v-model="form.occupation"
                                    placeholder="Occupation" />
                                <InputError :message="form.errors.occupation" />
                            </div>
                            <div class="flex-1">
                                <Label for="educational_level">Educational Level</Label>
                                <Input id="educational_level" type="text" :tabindex="16"
                                    v-model="form.educational_level" placeholder="Educational Level" />
                                <InputError :message="form.errors.educational_level" />
                            </div>
                        </div>

                        <div class="grid gap-6 mb-4">
                            <div class="grid gap-2">
                                <Label for="pre_address">Present Address</Label>
                                <Input id="pre_address" type="text" required autofocus :tabindex="17"
                                    autocomplete="pre_address" v-model="form.pre_address" placeholder="Address" />
                                <InputError :message="form.errors.pre_address" />
                            </div>
                        </div>

                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="pre_division">Division</Label>
                                <select id="pre_division"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    v-model="form.pre_division" :tabindex="18">
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
                                    v-model="form.pre_district" :tabindex="19">
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
                                    v-model="form.pre_thana" :tabindex="20">
                                    <option disabled value="">Select Thana</option>
                                    <option v-for="thana in pre_thanas" :key="thana.id" :value="thana.id">{{ thana.name
                                        }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.pre_thana" />
                            </div>
                            <div class="flex-1">
                                <Label for="pre_post_code">Post Code</Label>
                                <Input id="pre_post_code" type="text" required autofocus :tabindex="21"
                                    v-model="form.pre_post_code" placeholder="Post Code" />
                                <InputError :message="form.errors.pre_post_code" />
                            </div>
                        </div>

                        <!-- Before Permanent Address -->
                        <div class="flex items-center mb-2">
                            <input id="same_as_present" type="checkbox" v-model="sameAsPresent" class="mr-2" />
                            <Label for="same_as_present">Same as Present address</Label>
                        </div>

                        <div class="grid gap-6 mb-4 " v-if="!sameAsPresent">
                            <div class="grid gap-2">
                                <Label for="per_address">Permanent Address</Label>
                                <Input id="per_address" type="text" required autofocus :tabindex="22"
                                    autocomplete="per_address" v-model="form.per_address" placeholder="Address"
                                    :disabled="sameAsPresent" />
                                <InputError :message="form.errors.per_address" />
                            </div>
                        </div>

                        <div class="flex gap-6 mb-4" v-if="!sameAsPresent">
                            <div class="flex-1">
                                <Label for="per_division">Division</Label>
                                <select id="per_division"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    v-model="form.per_division" :tabindex="23">
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
                                    v-model="form.per_district" :tabindex="24">
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
                                    v-model="form.per_thana" :tabindex="25">
                                    <option disabled value="">Select Thana</option>
                                    <option v-for="thana in per_thanas" :key="thana.id" :value="thana.id">{{ thana.name
                                        }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.per_thana" />
                            </div>
                            <div class="flex-1">
                                <Label for="per_post_code">Post Code</Label>
                                <Input id="per_post_code" type="text" required autofocus :tabindex="26"
                                    v-model="form.per_post_code" placeholder="Post Code" />
                                <InputError :message="form.errors.per_post_code" />
                            </div>
                        </div>

                        <div class="grid gap-6 mt-10 mb-4">
                            <div class="flex items-start">
                                <input id="is_previous_member" type="checkbox" v-model="form.is_previous_member"
                                    class="mr-2 mt-1" :tabindex="27" />
                                <Label for="is_previous_member">
                                    Were you previously a member of this co-operative society?
                                </Label>
                            </div>
                            <div v-if="form.is_previous_member" class="grid gap-6 mt-4">
                                <div class="grid gap-2">
                                    <Label for="previous_member_number">Previous Member Number</Label>
                                    <Input id="previous_member_number" type="text" v-model="form.previous_member_number"
                                        placeholder="Enter Previous Member Number" :tabindex="28" />
                                    <InputError :message="form.errors.previous_member_number" />
                                </div>
                            </div>
                        </div>
                        <div class="grid gap-6 mt-10 mb-4">
                            <h2 class="text-lg font-semibold">Upload Images</h2>
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label for="profile_image">Profile Image</Label>
                                    <Input id="profile_image" type="file" accept="image/*"
                                        @change="(e: Event) => form.profile_image = (e.target as HTMLInputElement).files?.[0] || null"
                                        :tabindex="29" />
                                    <InputError :message="form.errors.profile_image" />
                                </div>
                            </div>
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label for="signature_image">Signature Image</Label>
                                    <Input id="signature_image" type="file" accept="image/*" required
                                        @change="(e: Event) => form.signature_image = (e.target as HTMLInputElement).files?.[0] || null"
                                        :tabindex="30" />
                                    <InputError :message="form.errors.signature_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Introducer Information Section -->
                <div class="border rounded-lg p-4 bg-gray-50 mt-10">
                    <h3 class="text-md font-semibold mb-4">Introducer Information</h3>
                    <div class="mt-4">
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="introducer_pin">Introducer PIN</Label>
                                <Input id="introducer_pin" type="text" required autofocus :tabindex="36"
                                    v-model="form.introducer_pin" placeholder="Introducer PIN" />
                                <InputError :message="form.errors.introducer_pin" />
                            </div>
                            <div class="flex-1 flex items-end">
                                <Button type="button" @click="checkIntroducer" :disabled="introducerLoading" class="ml-2">
                                    Check Introducer
                                </Button>
                            </div>
                        </div>
                        <div v-if="introducerLoading" class="text-blue-600 text-sm mb-2">Checking...</div>
                        <div v-if="introducerError" class="text-red-600 text-sm mb-2">{{ introducerError }}</div>
                        <div v-if="introducerInfo" class="bg-gray-100 p-2 rounded mb-2 text-sm">
                            <div><strong>Name:</strong> {{ introducerInfo.name }}</div>
                            <div><strong>Mobile:</strong> {{ introducerInfo.mobile }}</div>
                            <div><strong>Email:</strong> {{ introducerInfo.email }}</div>
                        </div>
                        <div class="flex gap-6 mb-4">
                            <div class="flex-1">
                                <Label for="introducer_signature">Upload Scan File</Label>
                                <Input id="introducer_signature" type="file" autofocus :tabindex="38"
                                    @change="(e: Event) => form.introducer_signature = (e.target as HTMLInputElement).files?.[0] || null" />
                                <InputError :message="form.errors.introducer_signature" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Introducer Information Section -->

                <!-- Earning Source Toggle -->
                <div class="flex items-center mb-4">
                    <span class="mr-4 font-medium">Do you have a earning source?</span>
                    <input type="checkbox" id="toggleSwitch" class="hidden" v-model="hasEarningSource" />

                    <label for="toggleSwitch"
                        class="select-none relative inline-flex h-6 w-14 items-center rounded-full transition-colors duration-200 cursor-pointer"
                        :class="hasEarningSource ? 'bg-[#0E947A]' : 'bg-[#E6E6E6]'">
                        <span class="absolute w-full text-xs transition-transform duration-200"
                            :class="hasEarningSource ? 'text-white text-left pl-2' : 'text-black text-right pr-1.5'">
                            {{ hasEarningSource ? 'ON' : 'OFF' }}
                        </span>
                        <span
                            class="inline-block w-[17px] h-[17px] transform rounded-full bg-white transition-transform duration-200"
                            :style="hasEarningSource ? 'transform: translateX(36px);' : 'transform: translateX(4px);'"></span>
                    </label>


                </div>

                <!-- Employer Details Section (conditionally shown) -->
                <div v-if="hasEarningSource" class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-md font-semibold mb-4">Employer Details</h3>
                    <div class="flex gap-6 mb-4">
                        <div class="flex-1">
                            <Label for="employer_name">Name</Label>
                            <Input id="employer_name" v-model="form.employer_name" type="text"
                                placeholder="Employer Name" />
                            <InputError :message="form.errors.employer_name" />
                        </div>
                        <div class="flex-1">
                            <Label for="employer_email">Email</Label>
                            <Input id="employer_email" v-model="form.employer_email" type="email"
                                placeholder="Employer Email" />
                            <InputError :message="form.errors.employer_email" />
                        </div>
                        <div class="flex-1">
                            <Label for="employer_phone">Phone</Label>
                            <Input id="employer_phone" v-model="form.employer_phone" type="text"
                                placeholder="Employer Phone" />
                            <InputError :message="form.errors.employer_phone" />
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <Label for="designation">Designation</Label>
                            <Input id="designation" v-model="form.designation" type="text" placeholder="Designation" />
                            <InputError :message="form.errors.designation" />
                        </div>
                        <div class="flex-[2]">
                            <Label for="employer_address">Address</Label>
                            <Input id="employer_address" v-model="form.employer_address" type="text"
                                placeholder="Employer Address" />
                            <InputError :message="form.errors.employer_address" />
                        </div>
                    </div>
                </div>
                <!-- End Employer Details Section -->

                <div class="flex justify-start mt-6">
                    <Button type="submit" class="bg-blue-500 text-white hover:bg-blue-600">
                        Submit
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>