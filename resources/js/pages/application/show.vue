<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
  application: any
}>();

const app = props.application;

// Breadcrumbs
const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Applications', href: '/applications' },
  { title: `${app.application_number}`, href: '' }
];
</script>

<template>
  <Head :title="`Application #${app.application_number}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xlbg-white rounded-xl shadow-md overflow-hidden p-8 mt-8">
      <!-- Application Header -->
      <div class="border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Application Details</h2>
        <div class="flex items-center mt-2">
          <span class="px-3 py-1 text-sm rounded-full" 
                :class="{
                  'bg-yellow-100 text-yellow-800': app.status === 'pending',
                  'bg-green-100 text-green-800': app.status === 'approved',
                  'bg-red-100 text-red-800': app.status === 'rejected'
                }">
            {{ app.status.charAt(0).toUpperCase() + app.status.slice(1).toLowerCase() }}
          </span>
          <span class="ml-4 text-gray-600">#{{ app.application_number }}</span>
        </div>
      </div>

      <!-- Application Info Cards -->
      <div class="grid grid-cols-1 gap-6">
        <!-- Application Information Card -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Application Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Application Date</p>
              <p class="font-medium">{{ app.application_date || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Type</p>
              <p class="font-medium">{{ app.model_type === 'App\\Models\\Loan' ? 'Loan Application' : 'Other Application' }}</p>
            </div>
            <div v-if="app.created_by_name">
              <p class="text-sm text-gray-500">Created By</p>
              <p class="font-medium">{{ app.created_by_name }}</p>
            </div>
            <div v-if="app.notes">
              <p class="text-sm text-gray-500">Notes</p>
              <p class="font-medium">{{ app.notes }}</p>
            </div>
          </div>
        </div>

        <!-- Applicant Information Card -->
        <div v-if="app.model_type === 'App\\Models\\Loan' && app.model?.member" class="bg-blue-50 p-6 rounded-lg border border-blue-200">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Applicant Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Member ID</p>
              <p class="font-medium">{{ app.model.member.id }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Full Name</p>
              <p class="font-medium">{{ app.model.member.name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Email</p>
              <p class="font-medium">{{ app.model.member.email ?? '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Phone</p>
              <p class="font-medium">{{ app.model.member.phone ?? '-' }}</p>
            </div>
            <div v-if="app.model.member.address">
              <p class="text-sm text-gray-500">Address</p>
              <p class="font-medium">{{ app.model.member.address }}</p>
            </div>
          </div>
        </div>

        <!-- Loan Information Card -->
        <div v-if="app.model_type === 'App\\Models\\Loan' && app.model" class="bg-green-50 p-6 rounded-lg border border-green-200">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Loan Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Loan Number</p>
              <p class="font-medium">{{ app.model.loan_number }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loan Amount</p>
              <p class="font-medium">{{ app.model.loan_amount }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Interest Rate</p>
              <p class="font-medium">{{ app.model.interest_rate }}%</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loan Term</p>
              <p class="font-medium">{{ app.model.loan_term_months }} months</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loan Purpose</p>
              <p class="font-medium">{{ app.model.loan_purpose }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loan Type</p>
              <p class="font-medium">{{ app.model.loan_type }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Installment Start Date</p>
              <p class="font-medium">{{ app.model.installment_start_date }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Status</p>
              <p class="font-medium capitalize">{{ app.model.status }}</p>
            </div>
          </div>
          
          <!-- Collateral Information -->
          <div v-if="app.model.loan_collateral_type" class="mt-6">
            <h4 class="text-md font-medium text-gray-700 mb-2">Collateral Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Collateral Type</p>
                <p class="font-medium capitalize">{{ app.model.loan_collateral_type.replace('_', ' ') }}</p>
              </div>
              <div v-if="app.model.loan_collateral_type === 'self_deposit'">
                <p class="text-sm text-gray-500">Self Deposit Amount</p>
                <p class="font-medium">{{ app.model.self_deposite_amount }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Family Members Table -->
        <div v-if="app.model_type === 'App\\Models\\Loan'">
          <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-2">Family Members</h3>
          <div v-if="app.model?.family_members?.length">
            <table class="min-w-full bg-white border border-gray-200 rounded mb-4">
              <thead>
                <tr class="bg-gray-100">
                  <th class="py-2 px-4 border-b">Relation</th>
                  <th class="py-2 px-4 border-b">Member ID</th>
                  <th class="py-2 px-4 border-b">Current Deposit</th>
                  <th class="py-2 px-4 border-b">Current Loan</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fm in app.model.family_members" :key="fm.id">
                  <td class="py-2 px-4 border-b">{{ fm.relation }}</td>
                  <td class="py-2 px-4 border-b">{{ fm.member_id }}</td>
                  <td class="py-2 px-4 border-b">{{ fm.current_deposit }}</td>
                  <td class="py-2 px-4 border-b">{{ fm.current_loan }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-500 mb-4">No family members found for this loan.</div>
        </div>

        <!-- Grantors Table -->
        <div v-if="app.model_type === 'App\\Models\\Loan'">
          <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-2">Grantors</h3>
          <div v-if="app.model?.grantors?.length">
            <table class="min-w-full bg-white border border-gray-200 rounded mb-4">
              <thead>
                <tr class="bg-gray-100">
                  <th class="py-2 px-4 border-b">Member ID</th>
                  <th class="py-2 px-4 border-b">Deposit Amount</th>
                  <th class="py-2 px-4 border-b">Loan Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="g in app.model.grantors" :key="g.id">
                  <td class="py-2 px-4 border-b">{{ g.member_id }}</td>
                  <td class="py-2 px-4 border-b">{{ g.deposit_amount }}</td>
                  <td class="py-2 px-4 border-b">{{ g.loan_amount }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-500 mb-4">No grantors found for this loan.</div>
        </div>
      </div>

      <!-- Back Button -->
      <div class="mt-8 text-center">
        <Link href="/applications" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Applications
        </Link>
      </div>
    </div>
  </AppLayout>
</template>