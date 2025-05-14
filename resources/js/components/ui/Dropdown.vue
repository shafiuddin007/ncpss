<template>
  <div class="relative gap-2">
    <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <select
      :id="id"
      :value="modelValue"
      
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement)?.value)"
      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
    >
      <option disabled value="">{{ placeholder }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { cn } from '@/lib/utils';

defineProps<{
  id: string;
  label: string;
  options: { value: string | number; label: string }[];
  modelValue: string | number | null;
  placeholder?: string;
  error?: string;
}>();

defineEmits(['update:modelValue']);
</script>
