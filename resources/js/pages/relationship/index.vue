<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';


const props = defineProps<{
    relations: { id: number, relationship_name_bn: string, relationship_name_en: string, is_active: number, is_deleted: number, created_at: Date, updated_at: Date }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Relationships',
        href: '/relationships',
    },
];
</script>

<template>
    <Head title="Relationships" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
           
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <table class="table-auto w-full border-collapse text-sm m-4">
                    <thead>
                        <tr>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Name Bangla</th>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Name English</th>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Status</th>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        <tr v-for="rel in relations" :key="rel.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 h-20">
                            <td>{{ rel.relationship_name_bn }}</td>
                            <td>{{ rel.relationship_name_en }}</td>
                            <td>{{ rel.is_active }}</td>
                            <td></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
