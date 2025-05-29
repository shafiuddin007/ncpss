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

const pagination = props.applications;

// For search and filter
const search = ref('');
const status = ref('');
const productType = ref('');

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
</script>

<template>
  <Head title="Applications" />
  <AppLayout>
    <div>
      <DataTable
        title="Applications"
        subtitle="List of all applications"
        :total="pagination.total"
        :pageStart="pagination.from"
        :pageEnd="pagination.to"
        :searchPlaceholder="'Search applications...'"
        v-model:search="search"
      >
        <!-- <template #header-action>
          <Link href="/applications/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            Add Application
          </Link>
        </template> -->
        <template #filter>
          <div class="flex gap-2">
            <select
              v-model="status"
              class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-auto"
            >
              <option value="">Status</option>
              <option v-for="option in props.statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
            <select
              v-model="productType"
              class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-auto"
            >
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
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800 px-2 py-1 rounded': app.status === 'pending',
                  'bg-green-100 text-green-800 px-2 py-1 rounded': app.status === 'approved',
                  'bg-red-100 text-red-800 px-2 py-1 rounded': app.status === 'rejected',
                  'bg-gray-100 text-gray-800 px-2 py-1 rounded': app.status === 'cancelled',
                  'bg-blue-100 text-blue-800 px-2 py-1 rounded': app.status === 'reset',
                  'bg-gray-200 text-gray-800 px-2 py-1 rounded': app.status === 'on_hold',
                  'bg-orange-100 text-orange-800 px-2 py-1 rounded': app.status === 'draft'

                }"
              >
                {{
                  props.statusOptions.find(option => option.value === app.status)?.label || app.status
                }}
              </span>
            </td>
            <td class="border px-2 py-1">
              <div class="flex justify-center gap-4">
              <Link :href="`/applications/${app.id}/show`" class="text-green-600">Details</Link>
              <Link :href="`/applications/${app.id}/history`" class="text-blue-600">Approval History</Link>
              <Link :href="`/applications/${app.id}/approval`" class="text-red-600">Action</Link>
              </div>
            </td>
          </tr>
        </template>
        <template #pagination>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <span v-for="link in pagination.links" :key="link.label">
              <Link
                v-if="link.url"
                :href="link.url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
                :class="{
                  'bg-indigo-50 text-indigo-600': link.active,
                  'text-gray-700 hover:bg-gray-50': !link.active
                }"
                v-html="link.label"
              />
              <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700" v-html="link.label" />
            </span>
          </nav>
        </template>
      </DataTable>
    </div>
  </AppLayout>
</template>