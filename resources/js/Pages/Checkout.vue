<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
defineProps({
    cart: {},
    tax: 0,
    total: 0,
});
</script>

<template>
    <AppLayout title="Checkout">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Checkout
            </h2>
        </template>
        <template #content>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="mt-8 text-2xl">
                                Bienvenido a la tienda en línea
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-2/3 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                        <div
                            class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8 dark:text-gray-100 text-gray-700 grid sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            {{ cart }}
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8">
                            <div class="flex flex-col gap-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-700 dark:text-gray-100">Subtotal</span>
                                    <span class="text-gray-700 dark:text-gray-100">$ {{ cart.subtotal }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700 dark:text-gray-100">IVA</span>
                                    <span class="text-gray-700 dark:text-gray-100">$ {{ tax }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700 dark:text-gray-100">Total a pagar</span>
                                    <span class="text-gray-700 dark:text-gray-100">$ {{ total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
