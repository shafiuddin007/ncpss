<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  member: any,
  loan: any,
  loanSchedule: any
}>();

const paymentType = ref('loan_repayment');
const checked = ref(false);

function fetchLoanSchedule() {
  // This is a placeholder if you want to fetch on demand
}

function makePayment() {
  if (!checked.value || !props.loanSchedule) return;
  router.post(
    route('loan-schedules.payment', { loanSchedule: props.loanSchedule.id }),
    {},
    {
      onSuccess: () => {
        router.visit(route('payment.confirmation', {
          member: props.member.id,
          loanSchedule: props.loanSchedule.id
        }));
      },
      onError: () => {
        alert('Payment failed!');
      }
    }
  );
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Payments',
        href: '',
    },
];

</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
  <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Payment for {{ props.member.name }}</h2>
    <div class="mb-4">
      <label class="block mb-1 font-medium">Payment Type</label>
      <select v-model="paymentType" class="border rounded px-3 py-2 w-full">
        <option value="loan_repayment">Loan Repayment</option>
        <option value="share_payment">Share Payment</option>
        <option value="savings_payment">Savings Payment</option>
        <option value="dps">DPS</option>
        <option value="hdps">HDPS</option>
      </select>
    </div>
    <button v-if="paymentType === 'loan_repayment'" @click="fetchLoanSchedule" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded">Go</button>

    <div v-if="paymentType === 'loan_repayment' && loan && loanSchedule" class="border rounded p-4 mt-4">
      <div class="mb-2"><strong>Due Date:</strong> {{ loanSchedule.due_date }}</div>
      <div class="mb-2"><strong>Principal:</strong> {{ loanSchedule.principal }}</div>
      <div class="mb-2"><strong>Interest:</strong> {{ loanSchedule.interest }}</div>
      <div class="mb-2"><strong>Total Payment:</strong> {{ loanSchedule.total_payment }}</div>
      <div class="mb-2">
        <label class="inline-flex items-center">
          <input type="checkbox" v-model="checked" class="mr-2" />
          Pay this amount
        </label>
      </div>
      <button :disabled="!checked" @click="makePayment" class="px-4 py-2 bg-green-600 text-white rounded disabled:opacity-50">Make Payment</button>
    </div>
    <div v-else-if="paymentType === 'loan_repayment' && (!loan || !loanSchedule)" class="text-red-600 mt-4">
      No active loan or no due payment for this month.
    </div>
  </div>
  </AppLayout>
</template>
