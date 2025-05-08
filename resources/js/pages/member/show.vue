<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Member',
        href: '/members',
    },
    {
        title: 'Details',
        href: '',
    },
];

// defineProps({
//     member: {
//         type: Object,
//         required: false, // Mark as optional to avoid runtime errors
//         default: () => ({}), // Provide a default empty object
//     },
// });

const props = defineProps({
    member: {
        type: Object,
        required: false, // Mark as optional to avoid runtime errors
        default: () => ({}), // Provide a default empty object
    },
});

console.log('Member:', props.member);
</script>

<template>

    <Head title="Member Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-gray-100">
            <div class="container mx-auto py-8">
                <div class="grid grid-cols-4 sm:grid-cols-12 gap-6 px-4">
                    <div class="col-span-4 sm:col-span-3">
                        <div class="bg-white shadow rounded-lg p-6">
                            <div class="flex flex-col items-center">
                                <img v-if="member.photo" :src="`/storage/${member.photo}`" alt="Member Photo"
                                    class="w-40 h-40 rounded-full object-cover border border-gray-300" />
                                </img>
                                <h1 class="text-xl font-bold">{{ member.name }}</h1>
                                <p class="text-gray-700">Has an active loan.</p>
                                <div class="mt-6 flex flex-wrap gap-4 justify-center">
                                    <a href="#"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Contact</a>
                                    <a href="#"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">Resume</a>
                                </div>
                            </div>
                            <hr class="my-6 border-t border-gray-300">
                            <div class="flex flex-col items-center text-center mt-6">
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Member's Signature
                                </h2>
                                <div class="w-48 h-32 border rounded-lg overflow-hidden shadow-md bg-gray-50 dark:bg-gray-800">
                                    <img v-if="member.signature" 
                                         :src="`/storage/${member.signature}`" 
                                         alt="Member's Signature" 
                                         class="w-full h-full object-contain p-2">
                                    <p v-else class="text-sm text-gray-500 dark:text-gray-400 p-4">
                                        No signature available
                                    </p>
                                </div>
                            </div>
                        </div>


                        <!-- Card start -->
                        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg mt-4">
                            <div class="border-b px-4 pb-6">
                                <div class="text-center my-4">
                                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                                        src="https://randomuser.me/api/portraits/women/21.jpg" alt="">
                                    <div class="py-2">
                                        <div>
                                            Nominee
                                        </div>
                                        <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">
                                            {{ member.nominees.nominee_name }}
                                        </h3>
                                        <div>
                                            <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">
                                                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400"
                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24" width="24" height="24">
                                                    <path class=""
                                                        d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z" />
                                                </svg>
                                                {{ member.nominees.relationship }}                         
                                            </div>
                                        </div>
                                        <div>
                                            <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">
                                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-600 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M6.62 10.79a15.91 15.91 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.11-.21 11.72 11.72 0 0 0 3.7 1.2 1 1 0 0 1 .88 1v3.17a1 1 0 0 1-1 1A16 16 0 0 1 3 5a1 1 0 0 1 1-1h3.17a1 1 0 0 1 1 .88 11.72 11.72 0 0 0 1.2 3.7 1 1 0 0 1-.21 1.11Z" />
                                                </svg>
                                                {{ member.nominees.contact_no }}
                                            </div>
                                        </div>
                                        <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-600 mr-1"
                                                fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" width="24" height="24">
                                                <path class=""
                                                    d="M5.64 16.36a9 9 0 1 1 12.72 0l-5.65 5.66a1 1 0 0 1-1.42 0l-5.65-5.66zm11.31-1.41a7 7 0 1 0-9.9 0L12 19.9l4.95-4.95zM12 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                            </svg>
                                            {{ member.nominees.address }} 
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Card end -->

                          <!-- Card start -->
                          <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg mt-4">
                            <div class="border-b px-4 pb-6">
                                <div class="text-center my-4">
                                   
                                    <div class="py-4 text-center">
                                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Introducer
                                        </h2>
                                        <h3 class="font-bold text-2xl text-blue-600 dark:text-blue-400 mb-4">
                                            <Link :href="`/members/${member.introducer.account_number}`">
                                                {{ member.introducer.name }}
                                            </Link>
                                        </h3>
                                        <div class="w-48 h-32 mx-auto border rounded-lg overflow-hidden shadow-md bg-gray-50 dark:bg-gray-800">
                                            <img v-if="member.introducer.signature" 
                                                 :src="`/storage/${member.introducer.signature}`" 
                                                 alt="Introducer's Signature" 
                                                 class="w-full h-full object-contain p-2">
                                            <p v-else class="text-sm text-gray-500 dark:text-gray-400 p-4">
                                                No signature available
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Card end -->


                    </div>
                    <div class="col-span-4 sm:col-span-9">
                        <div class="bg-white overflow-hidden shadow rounded-lg border">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Personal Information
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    This is some information about the member.
                                </p>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                                <dl class="sm:divide-y sm:divide-gray-200">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Full name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.name }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Father's name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.father_name }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Mother's name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.mother_name }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py -5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            NID
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.nid }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py -5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Date of birth
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.dob }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Birth Place
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.place_of_birth }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Gender
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.gender }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Marital status
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.marital_status }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Occupation
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.occupation }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Religion
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.religion }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Nationality
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.country?.name || 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Blood group
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.blood_group }}
                                        </dd>
                                    </div>

                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Email address
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.email }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Phone number
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.mobile }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Present Address
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.pre_address }}<br>
                                            {{ member.pre_thana?.thana_name_en || 'N/A' }},
                                            {{ member.pre_district?.district_name_en || 'N/A' }},
                                            {{ member.pre_division?.division_name_en || 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Permanent Address
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ member.per_address }}<br>
                                            {{ member.per_thana?.thana_name_en || 'N/A' }},
                                            {{ member.per_district?.district_name_en || 'N/A' }},
                                            {{ member.per_division?.division_name_en || 'N/A' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </AppLayout>
</template>