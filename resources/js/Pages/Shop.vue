<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    product_id: null,
    quantity: 0,
});

const addToCart = (id, quantity) => {
    form.product_id = id;
    form.quantity = quantity;
    form.post(route('cart.store'), {
        onSuccess: () => {
            console.log('success');
        },
    });
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tienda en línea
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                    <div
                        class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8 dark:text-gray-100 text-gray-700 grid sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        <template v-for="product in products.data">
                            <div class="border border-gray-300 dark:border-gray-700 rounded-lg">
                                <div class="flex flex-col items-center">
                                    <img class="w-full object-cover rounded-t-lg" :src="product.image" alt="">
                                    <div class="flex flex-col gap-2 text-center mt-2 p-4">
                                        <h1 class="text-xl uppercase font-semibold text-gray-700 dark:text-gray-200">{{ product.name }}</h1>
                                        <span class="text-gray-600 dark:text-gray-400 mt-1 text-2xl">$ {{ product.price }}</span>
                                        <button
                                            @click="addToCart(product.id, 1)"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                                            Add to cart
                                        </button>
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
