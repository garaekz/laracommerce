<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
defineProps({
    cart: {},
    tax: 0,
    total: 0,
});

const form = useForm({
    item_id: null,
    quantity: 1,
});

const handleQuantityUpdate = (item, quantity) => {
    form.item_id = item.id;
    form.quantity = quantity;
    form.put(route('cart.update'), {
        preserveState: true,
    })
}
</script>

<template>
    <AppLayout title="Checkout">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Shopping Cart
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                        <div
                            class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8 dark:text-gray-100 text-gray-700">
                            <table class="w-full">
                                <thead class="border-b border-gray-300 dark:border-gray-700">
                                    <tr class="font-black text-xl">
                                        <td class="px-3">Product</td>
                                        <td class="px-3">Price</td>
                                        <td class="px-3">Quantity</td>
                                        <td class="px-3">Total</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="text-gray-600 dark:text-gray-400"
                                        v-for="item in cart.items" :key="item.id">
                                        <td class="px-3 py-2 flex gap-3">
                                            <img
                                                :src="item.product.image"
                                                class="h-24 auto object-cover"
                                                :alt="`Image of product ${item.product.name}`">
                                            <div>
                                                <h3 class="text-xl font-semibold">{{ item.product.name }}</h3>
                                                <p class="text-sm">
                                                    {{ item.product.description }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            $ {{ item.product.price }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="flex flex-col">
                                                <div class="flex">
                                                <button
                                                @click="handleQuantityUpdate(item, item.quantity - 1)"
                                                class="px-2 text-2xl bg-gray-200 border-y border-l border-gray-400 dark:bg-gray-600 dark:border-gray-200 font-bold h-8">
                                                    -
                                                </button>
                                                <input
                                                readonly
                                                class="border-y border-gray-400 dark:border-gray-200 text-center w-12 dark:bg-gray-600 dark:text-gray-100 h-8"
                                                type="text" :value="item.quantity">
                                                <button
                                                @click="handleQuantityUpdate(item, item.quantity+1)"
                                                class="px-2 text-2xl bg-gray-200 border-y border-r border-gray-400 dark:bg-gray-600 dark:border-gray-200 font-bold h-8">
                                                    +
                                                </button>
                                            </div>
                                            <button
                                            @click="handleQuantityUpdate(item, 0)"
                                            class="text-sm text-red-500 hover:text-red-700 font-bold py-2">
                                                Remove
                                            </button>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">$ {{ item.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="my-8 flex justify-end">
                                <div class="flex flex-col">
                                    <h1 class="text-2xl font-bold">
                                        Subtotal: $ {{ cart.subtotal }}
                                    </h1>
                                    <h4 class="text-sm">
                                        Shipping & taxes are calculated at checkout
                                    </h4>
                                    <Link
                                        :href="route('checkout')"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 text-center
                                        ">
                                        Checkout
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
