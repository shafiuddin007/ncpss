<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { defineProps } from 'vue';
import { ref } from 'vue';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import Dropdown from '@/components/ui/Dropdown.vue';

const props = defineProps<{
    countries: { id: number, name: string }[];
}>();

const dateValue = ref([]);
const formatter = ref({
  date: "DD MMM YYYY",
  month: "MMM",
});


const genderList = [
                    { value: 'male', label: 'Male' },
                    { value: 'female', label: 'Female' },
                    { value: 'others', label: 'Others' }
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
    nationality: '',
    nid: '',
    
    religion: '',
    blood_group: '',
    marital_status: '',
    
    occupation: '', 
    guardian_occuption: '',
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
});

const submit = () => {
    form.post(route('members'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>

    <Head title="Add Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-20">

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                            v-model="form.name" placeholder="Full name" />
                        <InputError :message="form.errors.name" />
                    </div>
                </div>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="father_name">Father's Name</Label>
                        <Input id="father_name" type="text" required autofocus :tabindex="2" 
                            v-model="form.father_name" placeholder="Father's name" />
                        <InputError :message="form.errors.father_name" />
                    </div>
                </div>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="mother_name">Mother's Name</Label>
                        <Input id="mother_name" type="text" required autofocus :tabindex="3" 
                            v-model="form.mother_name" placeholder="Mother's name" />
                        <InputError :message="form.errors.mother_name" />
                    </div>
                </div>

                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="mobile">Mobile Number</Label>
                        <Input id="mobile" type="text" required autofocus :tabindex="4" autocomplete="contact"
                            v-model="form.mobile" placeholder="Mobile number" />
                        <InputError :message="form.errors.mobile" />
                    </div>
                </div>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="text" required autofocus :tabindex="5" autocomplete="contact"
                            v-model="form.email" placeholder="Email" />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>
                
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="dob">Date of Birth</Label>
                        <!-- <vue-tailwind-datepicker :formatter="formatter" v-model="dateValue" as-single /> -->
                        <Input id="dob" type="text" required autofocus :tabindex="6" 
                            v-model="form.dob" placeholder="Date of Birth" />
                        <InputError :message="form.errors.dob" />
                    </div>
                    <div class="flex-1">
                        <Label for="place_of_birth">Place of Birth</Label>
                        <Input id="place_of_birth" type="text" required autofocus :tabindex="7" 
                            v-model="form.place_of_birth" placeholder="Place of Birth" />
                        <InputError :message="form.errors.place_of_birth" />
                    </div>

                    <div class="flex-1">
                        <!-- <Label for="gender">Gender</Label> -->
                        <Dropdown
                            id="gender"
                            label="Gender"
                            :options=genderList
                            v-model="form.gender"
                            placeholder="Select Gender"
                            :error="form.errors.gender"
                        />
                    </div>
                    
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="religion">Religion</Label>
                        <Input id="religion" type="text" required autofocus :tabindex="8" 
                            v-model="form.religion" placeholder="Religion" />
                        <InputError :message="form.errors.religion" />
                    </div>
                    <div class="flex-1">
                        <Label for="blood_group">Blood Group</Label>
                        <Input id="blood_group" type="text" required autofocus :tabindex="9" 
                            v-model="form.blood_group" placeholder="Blood Group" />
                        <InputError :message="form.errors.blood_group" />
                    </div>
                    <div class="flex-1">
                        <Label for="marital_status">Marital Status</Label>
                        <Input id="marital_status" type="text" required autofocus :tabindex="10" 
                            v-model="form.marital_status" placeholder="Marital Status" />
                        <InputError :message="form.errors.marital_status" />
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="nationality">Nationality</Label>
                        <select id="nationality" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" v-model="form.nationality" placeholder="Nationality">
                            <option disabled value="">Select Nationality</option>
                            <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
                        </select>
                        <InputError :message="form.errors.nationality" />
                    </div>
                    <div class="flex-1">
                        <Label for="nid">National ID Number</Label>
                        <Input id="nid" type="text" required autofocus :tabindex="7" 
                            v-model="form.nid" placeholder="National ID Number" />
                        <InputError :message="form.errors.nid" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="occupation">Occupation</Label>
                        <Input id="occupation" type="text" required autofocus :tabindex="8" 
                            v-model="form.occupation" placeholder="Occupation" />
                        <InputError :message="form.errors.occupation" />
                    </div>
                    <div class="flex-1">
                        <Label for="educational_level">Educational Level</Label>
                        <Input id="educational_level" type="text" required autofocus :tabindex="9" 
                            v-model="form.educational_level" placeholder="Educational Level" />
                        <InputError :message="form.errors.educational_level" />
                    </div>
                    
                </div>

                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="pre_address">Present Address</Label>
                        <Input id="pre_address" type="text" required autofocus :tabindex="5" autocomplete="pre_address"
                            v-model="form.pre_address" placeholder="Address" />
                        <InputError :message="form.errors.pre_address" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="pre_division">Division</Label>
                        <Input id="pre_division" type="text" required autofocus :tabindex="8" 
                            v-model="form.pre_division" placeholder="Division" />
                        <InputError :message="form.errors.pre_division" />
                    </div>
                    <div class="flex-1">
                        <Label for="pre_district">District</Label>
                        <Input id="pre_district" type="text" required autofocus :tabindex="8" 
                            v-model="form.pre_district" placeholder="District" />
                        <InputError :message="form.errors.pre_district" />
                    </div>
                    
                    <div class="flex-1">
                        <Label for="pre_thana">Thana</Label>
                        <Input id="pre_thana" type="text" required autofocus :tabindex="9" 
                            v-model="form.pre_thana" placeholder="Thana" />
                        <InputError :message="form.errors.pre_thana" />
                    </div>
                    <div class="flex-1">
                        <Label for="pre_post_code">Post Code</Label>
                        <Input id="pre_post_code" type="text" required autofocus :tabindex="9" 
                            v-model="form.pre_post_code" placeholder="Post Code" />
                        <InputError :message="form.errors.pre_post_code" />
                    </div>
                    
                </div>

                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="per_address">Permanent Address</Label>
                        <Input id="per_address" type="text" required autofocus :tabindex="5" autocomplete="per_address"
                            v-model="form.per_address" placeholder="Address" />
                        <InputError :message="form.errors.per_address" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-1">
                        <Label for="per_division">Division</Label>
                        <Input id="per_division" type="text" required autofocus :tabindex="8" 
                            v-model="form.per_division" placeholder="Division" />
                        <InputError :message="form.errors.per_division" />
                    </div>
                    <div class="flex-1">
                        <Label for="per_district">District</Label>
                        <Input id="per_district" type="text" required autofocus :tabindex="8" 
                            v-model="form.per_district" placeholder="District" />
                        <InputError :message="form.errors.per_district" />
                    </div>
                    
                    <div class="flex-1">
                        <Label for="per_thana">Thana</Label>
                        <Input id="per_thana" type="text" required autofocus :tabindex="9" 
                            v-model="form.per_thana" placeholder="Thana" />
                        <InputError :message="form.errors.per_thana" />
                    </div>
                    <div class="flex-1">
                        <Label for="per_post_code">Post Code</Label>
                        <Input id="per_post_code" type="text" required autofocus :tabindex="9" 
                            v-model="form.per_post_code" placeholder="Post Code" />
                        <InputError :message="form.errors.per_post_code" />
                    </div>
                    
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