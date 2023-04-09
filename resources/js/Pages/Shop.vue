<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    console.log(props.products);
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tienda en línea
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                    <div
                        class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8 dark:text-gray-100 text-gray-700 grid sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        <template v-for="product in products.data">
                            <div class="border border-gray-300 dark:border-gray-700 rounded-lg">
                                <div class="flex flex-col items-center">
                                    <img class="w-full object-cover rounded-t-lg" :src="product.image" alt="">
                                    <div class="text-center mt-2 p-4">
                                        <h1 class="text-xl uppercase font-semibold text-gray-700 dark:text-gray-200">{{ product.name }}</h1>
                                        <span class="text-gray-600 dark:text-gray-400 mt-1 text-2xl">$ {{ product.price }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
