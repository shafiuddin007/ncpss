<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head} from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
  member: any,
  loanSchedules: Array<{
    id: number,
    due_date: string,
    total_payment: number,
    paid: boolean,
    paid_date: string | null,
    principal: number, // Added principal property
    interest?: number, // Optionally add other properties used in the template
    due?: number,
    fine?: number
  }>
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Members', href: '/members' },
  { title: 'Loan Schedule', href: '' }
];
</script>

<template>
    <Head title="Products" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto px-4 py-8">
    
      <!-- <h2 class="text-xl font-bold mb-4">Loan Schedule for {{ props.member.name }}</h2> -->
       <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 gap-4">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Loan Issue For:</p>
                    <h2 class="text-lg sm:text-xl font-bold mt-2">{{ props.member.name }}</h2>
                    <p class="text-xs sm:text-sm text-gray-600">Member Id : {{ props.member.id }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Address : {{ props.member.pre_address }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Email: {{ props.member.email }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Phone: {{ props.member.mobile }}</p>
                </div>
        </div>
       <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="w-full border rounded">
        <thead>
          <tr class="bg-gray-100">           
            <th class="px-4 py-2 text-left">Payment Date</th>
            <th class="px-4 py-2 text-center">Repaid</th>
            <th class="px-4 py-2 text-center">Balance</th>
            <th class="px-4 py-2 text-left">Interest</th>            
            <th class="px-4 py-2 text-right">Due</th>
            <th class="px-4 py-2 text-right">Fine</th>
            <th class="px-4 py-2 text-right">LPS</th>
            <th class="px-4 py-2 text-center">Paid</th>
            
          </tr>
        </thead>
        <tbody>
          <tr v-for="schedule in props.loanSchedules" :key="schedule.id">
            <td class="px-4 py-2 text-center">{{ schedule.paid_date || '-' }}</td>
            <td class="px-4 py-2">{{ schedule.principal }}</td>
            <td class="px-4 py-2">10000</td>
            <td class="px-4 py-2">{{ schedule.interest }}</td>
            
            <td class="px-4 py-2 text-right">{{ schedule.due }}</td>
            <td class="px-4 py-2 text-right">{{ schedule.fine }}</td>
            <td class="px-4 py-2 text-right">LPS</td>
            <td class="px-4 py-2 text-center">
              <span :class="schedule.paid ? 'text-green-600 font-bold' : 'text-red-600 font-bold'">
                {{ schedule.paid ? 'Yes' : 'No' }}
              </span>
            </td>
            
          </tr>
          <tr v-if="props.loanSchedules.length === 0">
            <td colspan="4" class="text-center py-4 text-gray-500">No loan schedule found.</td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>
  </AppLayout>
</template>
