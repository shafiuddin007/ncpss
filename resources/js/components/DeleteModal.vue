<script setup lang="ts">
import { ref } from 'vue';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

const props = defineProps({
    title: {
        type: String,
        default: 'Are you sure?',
    },
    description: {
        type: String,
        default: 'This action cannot be undone. Please confirm to proceed.',
    },
    confirmText: {
        type: String,
        default: 'Delete',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    onConfirm: {
        type: Function,
        required: true,
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(false);

const closeModal = () => {
    isOpen.value = false;
};

const handleConfirm = () => {
    props.onConfirm(closeModal);
};
</script>

<template>
    <Dialog v-model="isOpen">
        <!-- Use the trigger slot for custom triggers like TrashIcon -->
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent aria-describedby="delete-modal-description">
            <div>
                <DialogHeader>
                    <DialogTitle>{{ title }}</DialogTitle>
                    <!-- Add a valid id for the description -->
                    <p id="delete-modal-description" class="text-sm text-gray-600 dark:text-gray-400">
                        {{ description }}
                    </p>
                </DialogHeader>
                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary">{{ cancelText }}</Button>
                    </DialogClose>
                    <Button variant="destructive" :disabled="processing" @click="handleConfirm">
                        {{ confirmText }}
                    </Button>
                </DialogFooter>
            </div>
        </DialogContent>
    </Dialog>
</template>