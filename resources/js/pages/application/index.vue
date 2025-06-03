<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/ui/table/ApplicationDataTable.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
  applications: any,
  statusOptions: Array<{ value: string, label: string }>,
  productTypeOptions: Array<{ value: string, label: string }>
}>();

const breadcrumbs = [
  { title: 'Applications', href: '/applications' },
];

const pagination = props.applications;

// For search and filter
const search = ref('');
const status = ref('');
const productType = ref('');

// Approval History Modal State
const showHistoryModal = ref(false);
type ApprovalHistory = {
  id: number;
  approval_step: string | number;
  approval_role: string;
  status: string;
  remarks: string;
  document?: string; // Optional, if documents are included in history
  created_at?: string;
  created_by_name?: string;
};

const approvalHistories = ref<ApprovalHistory[]>([]);
const selectedAppNumber = ref('');
const loadingHistory = ref(false);

async function openHistoryModal(app: { id: number; application_number: string }) {
  showHistoryModal.value = true;
  selectedAppNumber.value = app.application_number;
  loadingHistory.value = true;
  approvalHistories.value = [];
  try {
    const res = await fetch(`/applications/${app.id}/approval-history`);
    const data = await res.json();
    approvalHistories.value = data.histories;
  } catch (e) {
    approvalHistories.value = [];
  }
  loadingHistory.value = false;
}

function closeHistoryModal() {
  showHistoryModal.value = false;
  approvalHistories.value = [];
  selectedAppNumber.value = '';
}

// Open document helper
function openDocument(documentUrl: string) {
  if (documentUrl) {
    window.open(documentUrl, '_blank');
  }
}

// Watchers for search and filter, trigger Inertia visit
watch([search, status, productType], ([newSearch, newStatus, newProductType]) => {
  router.get(
    route('applications.index'), // or '/applications' if not using Ziggy
    {
      search: newSearch,
      status: newStatus,
      product_type: newProductType,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
});


// Action Modal State
const showActionModal = ref(false);
const actionApp = ref<{ id: number; application_number: string } | null>(null);
const actionStatus = ref('');
const actionRemarks = ref('');
const actionDocument = ref<File | null>(null);
const actionLoading = ref(false);
const actionError = ref('');

function openActionModal(app: { id: number; application_number: string }) {
  showActionModal.value = true;
  actionApp.value = app;
  actionStatus.value = '';
  actionRemarks.value = '';
  actionDocument.value = null;
  actionError.value = '';
}

function closeActionModal() {
  showActionModal.value = false;
  actionApp.value = null;
}

async function submitAction() {
  if (!actionApp.value || !actionStatus.value) {
    actionError.value = 'Please select a status';
    return;
  }

  actionLoading.value = true;
  actionError.value = '';

  try {
    const formData = new FormData();
    formData.append('status', actionStatus.value);
    formData.append('remarks', actionRemarks.value);
    if (actionDocument.value) {
      formData.append('document', actionDocument.value);
    }

    const response = await fetch(`/applications/${actionApp.value.id}/approval-action`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Failed to submit action');
    }

    // Refresh the page or update the application status in the table
    window.location.reload();
  } catch (error) {
    actionError.value = error instanceof Error ? error.message : 'An error occurred';
  } finally {
    actionLoading.value = false;
  }
}
</script>

<template>

  <Head title="Applications" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div>
      <DataTable title="Applications" subtitle="List of all applications" :total="pagination.total"
        :pageStart="pagination.from" :pageEnd="pagination.to" :searchPlaceholder="'Search applications...'"
        v-model:search="search">
        <!-- <template #header-action>
          <Link href="/applications/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            Add Application
          </Link>
        </template> -->
        <template #filter>
          <div class="flex gap-2">
            <select v-model="status" class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-auto">
              <option value="">Status</option>
              <option v-for="option in props.statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
            <select v-model="productType" class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-auto">
              <option value="">Product Type</option>
              <option v-for="option in props.productTypeOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>
        </template>
        <template #thead>
          <th class="border px-2 py-1">#</th>
          <th class="border px-2 py-1">Application Number</th>
          <th class="border px-2 py-1">Type</th>
          <th class="border px-2 py-1">Status</th>
          <th class="border px-2 py-1">Actions</th>
        </template>
        <template #tbody>
          <tr v-for="app in pagination.data" :key="app.id">
            <td class="border px-2 py-1 text-center">{{ app.id }}</td>
            <td class="border px-2 py-1 text-center">{{ app.application_number }}</td>
            <td class="border px-2 py-1 text-center">
              {{ app.model_type === 'App\\Models\\Loan' ? 'Loan' : app.model_type }}
            </td>
            <td class="border px-2 py-1 capitalize text-center">
              <span :class="{
                'bg-yellow-100 text-yellow-800 px-2 py-1 rounded': app.status === 'pending',
                'bg-green-100 text-green-800 px-2 py-1 rounded': app.status === 'approved',
                'bg-red-100 text-red-800 px-2 py-1 rounded': app.status === 'rejected',
                'bg-gray-100 text-gray-800 px-2 py-1 rounded': app.status === 'cancelled',
                'bg-blue-100 text-blue-800 px-2 py-1 rounded': app.status === 'reset',
                'bg-gray-200 text-gray-800 px-2 py-1 rounded': app.status === 'on_hold',
                'bg-orange-100 text-orange-800 px-2 py-1 rounded': app.status === 'draft'

              }">
                {{
                  props.statusOptions.find(option => option.value === app.status)?.label || app.status
                }}
              </span>
            </td>
            <td class="border px-2 py-1">
              <div class="flex justify-center gap-4">
                <Link :href="`/applications/${app.id}/show`" class="text-green-600">Details</Link>
                <button @click="openHistoryModal(app)" class="text-blue-600 cursor-pointer">Approval History</button>
                <button @click="openActionModal(app)" class="text-red-600 cursor-pointer">Action</button>
              </div>
            </td>
          </tr>
        </template>
        <template #pagination>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <span v-for="link in pagination.links" :key="link.label">
              <Link v-if="link.url" :href="link.url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
                :class="{
                  'bg-indigo-50 text-indigo-600': link.active,
                  'text-gray-700 hover:bg-gray-50': !link.active
                }" v-html="link.label" />
              <span v-else
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                v-html="link.label" />
            </span>
          </nav>
        </template>
      </DataTable>
    </div>

    <!-- Approval History Modal -->
    <div v-if="showHistoryModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300">
      <div
        class="bg-white rounded-xl shadow-2xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 hover:scale-100 mx-0">
        <div class="sticky top-0 bg-white z-10 p-6 border-b border-gray-100 flex justify-between items-center">
          <h2 class="text-2xl font-semibold text-gray-800">Approval History for Application {{ selectedAppNumber }}
          </h2>
          <button @click="closeHistoryModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
          <div v-if="loadingHistory" class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-600">Loading approval history...</p>
          </div>

          <div v-else-if="Array.isArray(approvalHistories) && approvalHistories.length">
            <div class="relative overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="w-full text-left">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 text-gray-700">
                  <tr>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Step</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Role</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Remarks</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Document</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Create Date</th>
                    <th class="px-6 py-3 font-medium text-sm uppercase tracking-wider text-center">Create By</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr v-for="(h, index) in approvalHistories" :key="h.id"
                    :class="{ 'bg-gray-50': index % 2 === 0, 'hover:bg-blue-50 transition-colors duration-150': true }">
                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-900">{{ h.approval_step }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-center">{{ h.approval_role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span :class="{
                        'px-3 py-1 rounded-full font-medium capitalize': true,
                        'bg-green-100 text-green-800': h.status === 'approved',
                        'bg-red-100 text-red-800': h.status === 'rejected',
                        'bg-yellow-100 text-yellow-800': h.status === 'pending',
                        'bg-blue-100 text-blue-800': h.status === 'forwarded',
                      }">
                        {{ h.status }}
                      </span>
                    </td>
                    <td
                      class="px-6 py-4 text-gray-600 max-w-xs truncate hover:max-w-none hover:whitespace-normal text-center"
                      :title="h.remarks">
                      {{ h.remarks || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span v-if="h.document" class="text-blue-600 hover:underline cursor-pointer"
                        @click="openDocument(h.document)">View Document</span>
                      <span v-else class="text-gray-500">No Document</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-center">
                      {{ h.created_at ? new Date(h.created_at).toLocaleDateString('en-GB', {
                        day: '2-digit', month:
                          'short',
                        year: 'numeric' }).replace(/ /g, ' ') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-center">
                      {{ h.created_by_name || '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div v-else class="flex flex-col items-center justify-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600 text-lg">No approval history found for this application.</p>
            <p class="text-gray-500 mt-2">The approval history will appear here once available.</p>
          </div>
        </div>

        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-3 flex justify-end">
          <button @click="closeHistoryModal"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Close
          </button>
        </div>
      </div>
    </div>

      <!-- Action Modal -->
    <div v-if="showActionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all duration-300 scale-95 hover:scale-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex justify-between items-center">
          <div>
            <h2 class="text-xl font-semibold text-gray-800">Application #{{ actionApp?.application_number }}</h2>
            <p class="text-sm text-gray-500 mt-1">Review and submit your decision</p>
          </div>
          <button @click="closeActionModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitAction" class="p-6 space-y-6">
          <!-- Status Field -->
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Decision</label>
            <select 
              v-model="actionStatus" 
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiAjdjQgdjYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1jaGV2cm9uLWRvd24iPjxwYXRoIGQ9Im02IDkgNiA2IDYtNiIvPjwvc3ZnPg==')] bg-no-repeat bg-[right_0.75rem_center]"
              required
            >
              <option value="" disabled selected>Select your decision</option>
              <option 
                v-for="option in statusOptions" 
                :key="option.value" 
                :value="option.value"
                class="capitalize"
              >
                {{ option.label }}
              </option>
            </select>
          </div>

          <!-- Remarks Field -->
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Comments</label>
            <textarea 
              v-model="actionRemarks" 
              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200" 
              rows="4" 
              placeholder="Add any additional comments..."
            ></textarea>
          </div>

          <!-- File Upload -->
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Attach Supporting Document</label>
            <div class="flex items-center justify-center w-full">
              <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                <div class="flex flex-col items-center justify-center pt-5 pb-6" v-if="!actionDocument">
                  <svg class="w-8 h-8 mb-3 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                  </svg>
                  <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                  <p class="text-xs text-gray-500">PDF, DOC, JPG, PNG (MAX. 5MB)</p>
                </div>
                <div v-else class="p-4 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="mt-2 text-sm font-medium text-gray-700 truncate max-w-xs">{{ actionDocument.name }}</p>
                </div>
                <input 
                  type="file" 
                  @change="e => actionDocument = e.target.files?.[0] || null" 
                  class="hidden" 
                  accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                />
              </label>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="actionError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ actionError }}</span>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-3 pt-4">
            <button 
              type="button" 
              @click="closeActionModal" 
              class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="actionLoading"
              class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px]"
            >
              <svg v-if="actionLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ actionLoading ? 'Processing...' : 'Submit Decision' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>