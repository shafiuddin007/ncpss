<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  member: any,
  loan: any,
  loanSchedule: any,
  shareAccount?: any,
  sharePayment?: any,
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

const payments = reactive({
  share: 100,
  share_due: 3,
  loan_refund: 3,
  interest_on_loan: 3,
  loan_due_interest: 3,
  lps: 3,
  fine_on_loan: 3,
  savings_deposit: 3,
  fied_deposit: 3,
  hds: 3,
  hds_fine: 3,
  sdps: 3,
  sdps_fine: 3,
  welfare_fund: 3,
  education_fund: 3,
  graveyard_fund: 10,
});

const grandTotal = computed(() =>
  Object.values(payments).reduce((sum, val) => sum + (Number(val) || 0), 0)
);

const errors = ref<{ share?: string }>({});

function submitPayments() {
  errors.value = {};
  if (!payments.share || Number(payments.share) === 0) {
    errors.value.share = 'Share is required.';
    return;
  }
  if (Number(payments.share) % 100 !== 0) {
    errors.value.share = 'Share must be a multiple of 100.';
    return;
  }
  // You can send `payments` to backend here
  alert('Payments submitted: ' + JSON.stringify(payments));
}

</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
  <div class="bg-gray-100">
    <div class="contianer mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Make Payment</h1>
      <div class="bg-white p-6 rounded shadow-md">
        <form @submit.prevent="submitPayments">
          <table class="w-full mb-6">
            <thead>
              <tr>
                <th class="text-left">Description</th>
                <th class="text-left">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Share</td>
                <td>
                  <input v-model.number="payments.share" type="number" class="border rounded px-2 py-1 w-24" />
                  <div v-if="errors.share" class="text-red-600 text-xs mt-1">{{ errors.share }}</div>
                </td>
              </tr>
              <tr v-if="props.sharePayment && props.sharePayment.due && Number(props.sharePayment.due) !== 0">
                <td>Share Due</td>
                <td>
                  <input
                    type="number"
                    class="border rounded px-2 py-1 w-24"
                    :value="props.sharePayment.due"
                    readonly
                  />
                </td>
              </tr>
              <tr v-else>
                <td>Share Due</td>
                <td>
                  <input v-model.number="payments.share_due" type="number" class="border rounded px-2 py-1 w-24" readonly />
                </td>
              </tr>
              <tr>
                <td>Loan Refund</td>
                <td>
                  <input v-model.number="payments.loan_refund" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Interest on Loan</td>
                <td>
                  <input v-model.number="payments.interest_on_loan" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Loan Due Interest</td>
                <td>
                  <input v-model.number="payments.loan_due_interest" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>LPS</td>
                <td>
                  <input v-model.number="payments.lps" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Fine on Loan</td>
                <td>
                  <input v-model.number="payments.fine_on_loan" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Savings Deposit</td>
                <td>
                  <input v-model.number="payments.savings_deposit" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Fied Deposit</td>
                <td>
                  <input v-model.number="payments.fied_deposit" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>HDS</td>
                <td>
                  <input v-model.number="payments.hds" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>HDS Fine</td>
                <td>
                  <input v-model.number="payments.hds_fine" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>SDPS</td>
                <td>
                  <input v-model.number="payments.sdps" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>SDPS Fine</td>
                <td>
                  <input v-model.number="payments.sdps_fine" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Welfare Fund</td>
                <td>
                  <input v-model.number="payments.welfare_fund" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Education Fund</td>
                <td>
                  <input v-model.number="payments.education_fund" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
              <tr>
                <td>Graveyard Fund</td>
                <td>
                  <input v-model.number="payments.graveyard_fund" type="number" class="border rounded px-2 py-1 w-24" />
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="font-bold">
                <td>Grand Total</td>
                <td>{{ grandTotal }}</td>
              </tr>
            </tfoot>
          </table>
          <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
              Submit Payment
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </AppLayout>
</template>
