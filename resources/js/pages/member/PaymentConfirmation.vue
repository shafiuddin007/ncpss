<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Printer } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  member: any,
  loanSchedule: any
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Payments', href: '' },
  { title: 'Confirmation', href: '' }
];

function printReceipt() {
  window.print();
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="bg-white border rounded-lg shadow-lg px-6 py-8 max-w-md mx-auto mt-8 relative print:block"
      id="print-receipt"
    >
      <!-- Print Icon Button (absolute, right top corner, hide on print) -->
      <button
        @click="printReceipt"
        class="absolute right-4 top-4 text-gray-500 hover:text-blue-600 print:hidden"
        title="Print Receipt"
        style="z-index:10"
      >
        <Printer class="h-6 w-6 inline" />
      </button>
      <h1 class="font-bold text-2xl my-4 text-center text-blue-600">NCPSS Payment Receipt</h1>
      <hr class="mb-2">
      <div class="flex justify-between mb-6">
          <h1 class="text-lg font-bold">Invoice</h1>
          <div class="text-gray-700">
              <div>Date: 01/05/2023</div>
              <div>Invoice #: INV12345</div>
          </div>
      </div>
      <div class="mb-8">
          <h2 class="text-lg font-bold mb-4">Bill To:</h2>
          <div class="text-gray-700 mb-2">Member: {{ props.member.name }}</div>
          <div class="text-gray-700 mb-2">Mobile: {{ props.member.mobile }}</div>
          <div class="text-gray-700 mb-2">Member Code: {{ props.member.id }}</div>
      </div>
      <table class="w-full mb-8">
          <thead>
              <tr>
                  <th class="text-left font-bold text-gray-700">Description</th>
                  <th class="text-right font-bold text-gray-700">Amount</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td class="text-left text-gray-700">Principal</td>
                  <td class="text-right text-gray-700">{{ props.loanSchedule.principal }}</td>
              </tr>
              <tr>
                  <td class="text-left text-gray-700">Interest</td>
                  <td class="text-right text-gray-700">{{ props.loanSchedule.interest }}</td>
              </tr>
              <tr>
                  <td class="text-left text-gray-700">Due</td>
                  <td class="text-right text-gray-700">{{ props.loanSchedule.due }}</td>
              </tr>
              <tr>
                  <td class="text-left text-gray-700">Total Amount</td>
                  <td class="text-right text-gray-700">{{ props.loanSchedule.total_payment }}</td>
              </tr>
          </tbody>
          <tfoot>
              <tr>
                  <td class="text-left font-bold text-gray-700">Total</td>
                  <td class="text-right font-bold text-gray-700">$225.00</td>
              </tr>
          </tfoot>
      </table>
      <div class="text-gray-700 mb-2">Thank you for your business!</div>
      <div class="text-gray-700 text-sm">Received by {{ props.loanSchedule.payment_by }}, on {{ props.loanSchedule.paid_date }}</div>

      <!-- Replace router-link with Inertia Link for correct navigation -->
      <Link :href="route('member.list')" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded print:hidden">
        Back to Members
      </Link>
    </div>
    
  </AppLayout>
</template>

<style>
@media print {
  body * {
    visibility: hidden !important;
  }
  #print-receipt, #print-receipt * {
    visibility: visible !important;
  }
  #print-receipt {
    position: absolute !important;
    left: 0; top: 0; width: 100vw !important; min-width: 0 !important; max-width: 100vw !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    background: #fff !important;
    z-index: 99999 !important;
  }
  .print\:hidden {
    display: none !important;
  }
}
</style>
