<script setup>
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
    categories: [],
});
</script>
<template>
    <AppLayout title="Categories">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Category List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <h1
                        class="dark:text-gray-100 text-gray-500 font-bold text-lg mb-8">
                        Categories
                    </h1>

                    <div class="flex flex-col gap-2">
                        <span
                            class="dark:text-gray-100 text-gray-500 font-bold text-lg"
                            v-for="category in categories" :key="category.id">
                            {{ category.name }} - {{ category.slug }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
