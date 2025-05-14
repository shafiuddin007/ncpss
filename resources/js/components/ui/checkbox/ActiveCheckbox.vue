<script setup lang="ts">
import { computed } from 'vue'
import { Check } from 'lucide-vue-next'
import { CheckboxIndicator, CheckboxRoot } from 'radix-vue'
import { cn } from '@/lib/utils'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true,
  },
  class: {
    type: [String, Array, Object],
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const checked = computed(() => props.modelValue);

const handleChange = (val: boolean) => {
  emit('update:modelValue', val);
};
</script>

<template>
  <CheckboxRoot
    :checked="checked"
    @update:checked="handleChange"
    :class="cn(
      'peer size-5 shrink-0 rounded-sm border border-input ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground data-[state=checked]:border-accent-foreground',
      props.class
    )"
  >
    <CheckboxIndicator class="flex h-full w-full items-center justify-center text-current">
      <slot>
        <Check class="size-3.5 stroke-[3]" />
      </slot>
    </CheckboxIndicator>
  </CheckboxRoot>
</template>