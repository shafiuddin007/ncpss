<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
          
        </div>
        <div class="mt-4 md:mt-0">
          <button
            v-if="showAdd"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out"
            @click="$emit('add')"
          >
            Add Member
          </button>
        </div>
      </div>
      <!-- Search and Filter -->
      <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <!-- <p class="text-gray-500 mt-1">{{ subtitle }}</p> -->
        <div class="relative flex-grow sm:flex-none sm:w-1/2">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
          </div>
          <input
        type="text"
        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full"
        placeholder="Search by name, father's/mother's name, NID, or mobile..."
        v-model="search"
          >
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th v-for="col in columns" :key="col.key" :class="col.thClass" v-html="col.label"></th>
            <!-- <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> -->
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(row, idx) in paginatedRows"
            :key="row.id || idx"
            class="hover:bg-blue-50 transition-colors duration-150"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                  <img class="h-10 w-10 rounded-full object-cover" :src="row.avatar" alt="">
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ row.name }}</div>
                  <div class="text-sm text-gray-500">{{ row.mobile }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 text-center">{{ row.father_name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 text-center">{{ row.mother_name }}</div>
            </td>
             <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 text-center">{{ row.nid }}</div>
            </td>
            <!-- <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                  row.status === 'Active'
                    ? 'bg-green-100 text-green-800'
                    : row.status === 'On Leave'
                    ? 'bg-yellow-100 text-yellow-800'
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ row.status }}
              </span>
            </td> -->
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
              <a href="#" class="text-green-600 hover:text-green-900 mr-3" @click.prevent="$emit('view', row)">View</a>
              <a v-if="showEdit" href="#" class="text-indigo-600 hover:text-indigo-900 mr-3" @click.prevent="$emit('edit', row)">Edit</a>
              <a v-if="showDelete" href="#" class="text-red-600 hover:text-red-900" @click.prevent="$emit('delete', row)">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      <div class="flex items-center justify-between flex-col sm:flex-row">
        <div class="mb-4 sm:mb-0">
          <p class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ startRow }}</span>
            to
            <span class="font-medium">{{ endRow }}</span>
            of
            <span class="font-medium">{{ rows.length }}</span>
            results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a href="#" @click.prevent="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Previous</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </a>
            <a
              v-for="page in totalPages"
              :key="page"
              href="#"
              @click.prevent="goToPage(page)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',
                page === currentPage ? 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100' : 'bg-white text-gray-700 hover:bg-gray-50'
              ]"
            >{{ page }}</a>
            <a href="#" @click.prevent="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Next</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';

const props = defineProps<{
  title?: string,
  subtitle?: string,
  columns?: Array<{ key: string, label: string, thClass?: string }>,
  rows: Array<any>,
  departments?: string[],
  pageSize?: number,
  showAdd?: boolean,
  showEdit?: boolean,
  showDelete?: boolean,
}>();

const emit = defineEmits(['add', 'edit', 'delete', 'search', 'filter', 'sort', 'view']);

const search = ref('');
const selectedDepartment = ref('');
const currentPage = ref(1);

// Enhanced search: name, father's name, mother's name, NID, mobile
const filteredRows = computed(() => {
  let filtered = props.rows;

  if (search.value) {
    const s = search.value.toLowerCase();
    filtered = filtered.filter(row =>
      (row.name && row.name.toLowerCase().includes(s)) ||
      (row.father_name && row.father_name.toLowerCase().includes(s)) ||
      (row.mother_name && row.mother_name.toLowerCase().includes(s)) ||
      (row.nid && row.nid.toLowerCase().includes(s)) ||
      (row.mobile && row.mobile.toLowerCase().includes(s))
    );
  }

  if (props.departments && props.departments.length && selectedDepartment.value) {
    filtered = filtered.filter(row => row.department === selectedDepartment.value);
  }

  return filtered;
});

const pageSize = computed(() => props.pageSize || 5);
const totalPages = computed(() => Math.ceil(filteredRows.value.length / pageSize.value));
const paginatedRows = computed(() =>
  filteredRows.value.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)
);

const startRow = computed(() => (filteredRows.value.length === 0 ? 0 : (currentPage.value - 1) * pageSize.value + 1));
const endRow = computed(() => Math.min(currentPage.value * pageSize.value, filteredRows.value.length));

function prevPage() {
  if (currentPage.value > 1) currentPage.value--;
}
function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++;
}
function goToPage(page: number) {
  currentPage.value = page;
}

watch([search, selectedDepartment], () => {
  currentPage.value = 1;
  emit('search', search.value);
  emit('filter', selectedDepartment.value);
});
</script>