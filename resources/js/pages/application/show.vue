<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
  application: any
}>();

const app = props.application;

// Breadcrumbs
const breadcrumbs = [
  { title: 'Applications', href: '/applications' },
  { title: `${app.application_number}`, href: '' }
];

</script>

<template>

  <Head :title="`Application #${app.application_number}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mt-6 px-4 sm:px-6 lg:px-8">
      <!-- Application Header -->
      <div class="flex justify-between items-start mb-8">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Application: {{ app.application_number }}</h1>
          <div class="flex items-center mt-2 space-x-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" :class="{
              'bg-yellow-100 text-yellow-800': app.status === 'pending',
              'bg-green-100 text-green-800': app.status === 'approved',
              'bg-red-100 text-red-800': app.status === 'rejected'
            }">
              {{ app.status.charAt(0).toUpperCase() + app.status.slice(1).toLowerCase() }}
            </span>
            <span class="text-green-600 text-sm font-semibold">
              <svg class="w-4 h-4 inline mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Submitted on {{ app.application_date ? new Date(app.application_date).toLocaleDateString('en-GB', {
                day:
                  '2-digit', month: 'short', year: 'numeric'
              }) : '-' }}
            </span>
          </div>
        </div>
        <Link href="/applications"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Applications
        </Link>
      </div>

      <div class="space-y-6">
        <!-- Application Information Section -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-100 to-blue-300 px-6 py-4">
            <h2 class="text-lg font-semibold">Application Details</h2>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Application Type</p>
                <p class="text-sm font-medium text-gray-900 mt-1">
                  {{ app.model_type === 'App\\Models\\Loan' ? 'Loan Application' : 'Other Application' }}
                </p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.created_by_name || 'System' }}</p>
              </div>
              <div v-if="app.notes">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.notes }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Applicant Profile Section -->
        <div v-if="app.model?.member" class="bg-white shadow rounded-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-100 to-blue-300 px-6 py-4">
            <h2 class="text-lg font-semibold">Applicant Profile</h2>
          </div>
          <div class="p-6 mt-6 gap-6">
            <div class="flex flex-col md:flex-row gap-6">
              <!-- Profile Photo -->
              <div class="flex-shrink-0">
                <div class="relative">
                  <img :src="app.model.member.photo_url || '/default-user.png'" alt="Member Photo"
                    class="w-24 h-24 rounded-lg object-cover border-4 border-white shadow-md" />
                  <!-- <div class="absolute -bottom-2 -right-2 bg-blue-500 rounded-full p-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div> -->
                </div>
              </div>

              <!-- Profile Details -->
              <div class="flex-1 ">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ app.model.member.name }}</h3>
                    <div class="flex items-center mt-1 text-sm text-gray-600">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      {{ app.model.member.mobile || 'Not provided' }}
                    </div>
                    <div class="mt-3 md:mt-0">
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active Member
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="border-t border-gray-200 mt-6"></div>
          <!-- Member Information -->
          <div class="px-6 pb-6 mt-6 gap-6">
            <h5 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
              <!-- <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg> -->
              Member Information
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Member ID</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.member.id }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">National ID</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.member.nid || 'Not provided' }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.member.pre_address }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Member Since</p>
                <p class="text-sm font-medium text-gray-900 mt-1">
                  {{ app.model.member.created_at ? new Date(app.model.member.created_at).toLocaleDateString('en-GB', {
                    day: '2-digit', month: 'short', year: 'numeric'
                  }) : 'Not available' }}
                </p>
              </div>
            </div>
          </div>
          <div class="border-t border-gray-200 mt-6"></div>
          <!-- Employment Information -->
          <div class="px-6 pb-6 mt-6 gap-6">
            <h5 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
              <!-- <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg> -->
              Employment Information
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Occupation</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.occupation || '-' }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.designation || '-' }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Office Address</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.office_address || '-' }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Office Contact</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.office_contact || '-' }}</p>
              </div>
            </div>
          </div>
          <div class="border-t border-gray-200 mt-6"></div>

          <!-- Income Section -->
          <div class="px-6 pb-6 mt-6 gap-6">
            <h5 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
              <!-- <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg> -->
              Income Details
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Self Income</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.self_income || 0) }}</p>
              </div>
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Family Income</p>
                <p class="text-base font-semibold text-v-800 mt-1">BDT {{ (app.model.family_income || 0) }}</p>
              </div>
              <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">
                <p class="text-xs font-medium text-gray-700 uppercase tracking-wider">Total Income</p>
                <p class="text-base font-bold text-gray-900 mt-1">
                  BDT {{ (app.model.total_income || (parseFloat(app.model.self_income || 0) +
                    parseFloat(app.model.family_income || 0))) }}
                </p>
              </div>
            </div>
          </div>
          <div class="border-t border-gray-200 mt-6"></div>
          <!-- Expenses Section -->
          <div class="px-6 pb-6 mt-6 gap-6">
            <h5 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
              <!-- <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
              </svg> -->
              Expense Details
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Rent</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.rent || 0) }}</p>
              </div>
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Food</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.food_expense || 0) }}</p>
              </div>
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Education</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.education_expense || 0) }}</p>
              </div>
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Transport</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.transport_expense || 0) }}</p>
              </div>
              <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider">Other</p>
                <p class="text-base font-semibold text-gray-800 mt-1">BDT {{ (app.model.other_expense || 0) }}</p>
              </div>
              <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">
                <p class="text-xs font-medium text-gray-700 uppercase tracking-wider">Total Expenses</p>
                <p class="text-base font-bold text-gray-900 mt-1">
                  BDT {{ (app.model.total_expense ||
                    (parseFloat(app.model.rent || 0) +
                      parseFloat(app.model.food_expense || 0) +
                      parseFloat(app.model.education_expense || 0) +
                      parseFloat(app.model.transport_expense || 0) +
                      parseFloat(app.model.other_expense || 0))) }}
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Loan Information Section -->
        <div v-if="app.model_type === 'App\\Models\\Loan' && app.model"
          class="bg-white shadow rounded-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-100 to-blue-300 px-6 py-4">
            <h2 class="text-lg font-semibold">Loan Details</h2>
          </div>
          <div class="p-6">
            <!-- Product Information -->
            <div v-if="app.model.product" class="px-6 pb-6 mt-6 gap-6">
              <h3 class="text-md font-medium text-gray-900 mb-4">Product Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.product.name || '-' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Amount</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">BDT {{ (app.model.min_balance || 0) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Maximum Amount</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">BDT {{ (app.model.max_loan_amount || 0) }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Interest Rate</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">{{ app.model.interest_rate || '0' }}%</p>
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 mt-6"></div>

            <!-- Loan Application Information -->
            <div class="px-6 pb-6 mt-6 gap-6">
              <h3 class="text-md font-medium text-gray-900 mb-4">Loan Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Loan Amount</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.loan_amount }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Purpose</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ (app.model.loan_purpose || '-') }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Type</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ (app.model.loan_type || '-') }} </p>
                </div>
                <div v-if="app.model.loan_type === 'Urgent'">
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Urgent Fee</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ (app.model.urgent_fee || '0') }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Installment</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.total_installment || '0' }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Installment Start Date</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">
                    {{
                      app.model.installment_start_date
                        ? new Date(app.model.installment_start_date).toLocaleDateString('en-GB', {
                          day: '2-digit',
                          month: 'short',
                          year: 'numeric'
                        }).replace(/(\d{2}) (\w{3}) (\d{4})/, (m, d, mth, y) => `${d} ${mth} ${y}`)
                        : '-'
                    }}
                  </p>
                </div>
              </div>
            </div>

            <div v-if="app.model.other_loan_amount > 0" class="border-t border-gray-200 mt-6"></div>
            <!-- Other Loan -->
            <div v-if="app.model.other_loan_amount > 0" class="px-6 pb-6 mt-6 gap-6">
              <h3 class="text-md font-medium text-gray-900 mb-4">Other Loan</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Other Loan Amount</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.other_loan_amount }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Purpose</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.other_loan_installment }} </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Type</p>
                  <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.other_loan_type }} </p>
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 mt-6"></div>
            <!-- Collateral Information -->
            <div v-if="app.model.loan_collateral_type" class="px-6 pb-6 mt-6 gap-6">
              <h3 class="text-md font-medium text-gray-900 mb-4">Collateral Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Collateral Type</p>
                  <p class="text-sm font-medium text-gray-900 mt-1 capitalize">
                    {{ app.model.loan_collateral_type.replace('_', ' ') }}
                  </p>
                </div>
                <div v-if="app.model.loan_collateral_type === 'self_deposit'">
                  <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Self Deposit Amount</p>
                  <p class="text-sm font-medium text-gray-900 mt-1">
                    BDT {{ (app.model.self_deposite_amount || 0) }}
                  </p>
                </div>
              </div>


            </div>

            <!-- Grantors Section -->
            <div v-if="app.model?.grantors?.length" class="bg-white shadow rounded-lg overflow-hidden">
              <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Grantors</h2>
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member
                        ID
                      </th>
                      <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit
                        Amount</th>
                      <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan
                        Amount
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="g in app.model.grantors" :key="g.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ g.member_id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">BDT {{ (g.deposit_amount) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">BDT {{ (g.loan_amount) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-if="!app.model?.grantors?.length" class="bg-white shadow rounded-lg overflow-hidden">
              <div class="p-6 text-center text-gray-500">
                No grantors found for this loan application.
              </div>
            </div>
          </div>
        </div>

        <!-- Family Members Section -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-100 to-blue-200 px-6 py-4">
            <h2 class="text-lg font-semibold ">Family Members</h2>
          </div>
          <div class="px-6 pb-6 mt-6 gap-6">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Family Member</p>
            <p class="text-sm font-medium text-gray-900 mt-1"> {{ app.model.family_member }} </p>
          </div>
          <div class="overflow-x-auto"
            v-if="app.model_type === 'App\\Models\\Loan' && app.model?.family_members?.length">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Relation
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Member ID
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Current Deposit
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Current Loan
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="fm in app.model.family_members" :key="fm.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ fm.relation }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ fm.member_id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">BDT {{ (fm.current_deposit) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">BDT {{ (fm.current_loan) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Empty State Messages -->
          <div v-if="app.model_type === 'App\\Models\\Loan' && !app.model?.family_members?.length"
            class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 text-center text-gray-500">
              No family members found for this loan application.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
