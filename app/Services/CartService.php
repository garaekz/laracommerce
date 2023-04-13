<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CartItemRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;
use Exception;

class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository,
    ) {
    }

    public function getWithItems(string $cookie_id): Cart
    {
        $cart = $this->cartRepository->findBy(
            ['cookie_cart_id' => $cookie_id],
            ['items.product']);
        if (!$cart) {
            throw new Exception('Cart not found');
        }
        return $cart->load('items.product');
    }

    public function count(string $cookie_id): int
    {
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);
        if (!$cart) {
            return 0;
        }
        return $cart->items->sum('quantity');
    }

    public function update(array $data, $cookie_id)
    {
        $cart = $this->getOrCreate($data, $cookie_id);
        $this->updateCartItemQuantity($data);
        return $cart->load('items.product');
    }

    public function addItem(array $data, $cookie_id) {
        $cart =  $this->getOrCreate($data, $cookie_id);
        $existingItem = $this->cartItemRepository->findBy([
            'cart_id' => $cart->id,
            'product_id' => $data['product_id']
        ]);

        if ($existingItem) {
            $existingItem->quantity += $data['quantity'];
            $this->cartItemRepository->update($existingItem->toArray(), $existingItem);
            return $cart;
        }

        $item = $this->cartItemRepository->create([
            'cart_id' => $cart->id,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity']
        ]);

        $cart->items()->save($item);
        return $cart;
    }

    public function delete(string $cookie_id): ?bool
    {
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);
        if (!$cart) {
            throw new Exception('Cart not found');
        }
        return $this->cartRepository->delete($cart);
    }

    public function getForCheckout(string $cookie_id): Cart
    {
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);
        if (!$cart) {
            throw new Exception('Cart not found');
        }
        return $cart->load('items.product');
    }

    private function getOrCreate(array $data, string $cookie_id): Cart
    {
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);

        if($cart) return $cart;

        $payload = ['cookie_cart_id' => $cookie_id];
        if (isset($data['user_id'])) {
            $payload['user_id'] = $data['user_id'];
        }

        return $this->cartRepository->create($payload);
    }

    private function updateCartItemQuantity(array $data): bool
    {
        $cartItem = $this->cartItemRepository->find($data['item_id']);

        if (!$cartItem) {
            throw new Exception('Item not found in cart');
        }

        return $this->updateExistingCartItem($cartItem, $data);
    }

    private function updateExistingCartItem($cartItem, array $data): bool
    {
        $cartItem->quantity = (int) $data['quantity'];
        if ($cartItem->quantity === 0) {
            return $this->cartItemRepository->delete($cartItem);
        }
        return $this->cartItemRepository->update($cartItem->toArray(), $cartItem);
    }
}
