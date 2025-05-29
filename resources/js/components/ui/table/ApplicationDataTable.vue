<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
          <p class="text-gray-500 mt-1">{{ subtitle }}</p>
        </div>
        <div class="mt-4 md:mt-0">
          <slot name="header-action"></slot>
        </div>
      </div>
      <!-- Search and Filter -->
      <div class="mt-6 flex flex-col sm:flex-row gap-4 w-full sm:w-3/4">
        <div class="relative flex-grow">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
          <input
            :value="props.search"
            @input="emit('update:search', ($event.target as HTMLInputElement)?.value)"
            type="text"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full"
            :placeholder="searchPlaceholder"
          >
        </div>
        <div>
          <slot name="filter"></slot>
        </div>
      </div>
    </div>
    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <slot name="thead"></slot>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <slot name="tbody"></slot>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      <div class="flex items-center justify-between flex-col sm:flex-row">
        <div class="mb-4 sm:mb-0">
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ pageStart }}</span> to <span class="font-medium">{{ pageEnd }}</span> of <span class="font-medium">{{ total }}</span> results
          </p>
        </div>
        <div>
          <slot name="pagination"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

const props = defineProps<{
  title: string,
  subtitle?: string,
  total: number,
  pageStart: number,
  pageEnd: number,
  searchPlaceholder?: string,
  search?: string,
}>();

const emit = defineEmits(['update:search']);
</script>