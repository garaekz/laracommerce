<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CartItemRepositoryInterface;
use App\Models\Cart;
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
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);
        if (!$cart) {
            throw new Exception('Cart not found');
        }
        return $cart;
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
        $cart = $this->updateOrCreate($data, $cookie_id);
        $this->addOrUpdateCartItem($data, $cart);
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

    private function updateOrCreate(array $data, string $cookie_id): Cart
    {
        $cart = $this->cartRepository->findBy(['cookie_cart_id' => $cookie_id]);
        if (!$cart) {
            $payload = ['cookie_cart_id' => $cookie_id];
            if (isset($data['user_id'])) {
                $payload['user_id'] = $data['user_id'];
            }
            $cart = $this->cartRepository->create($payload);
        }

        return $cart;
    }

    private function addOrUpdateCartItem(array $data, Cart $cart): bool
    {
        $cartItem = $this->cartItemRepository->findBy([
            'product_id' => $data['product_id'],
            'cart_id' => $cart->id,
        ]);

        if ($cartItem) {
            return $this->updateExistingCartItem($cartItem, $data);
        }

        if ($data['quantity'] === 0) {
            return false;
        }

        return $this->createCartItem($data, $cart);
    }

    private function updateExistingCartItem($cartItem, array $data): bool
    {
        $cartItem->quantity = $data['quantity'];
        if ($cartItem->quantity === 0) {
            return $this->cartItemRepository->delete($cartItem);
        }
        return $this->cartItemRepository->update($cartItem->toArray(), $cartItem);
    }

    private function createCartItem(array $data, Cart $cart): bool
    {
        $newCartItem = $this->cartItemRepository->create([
            'product_id' => $data['product_id'],
            'cart_id' => $cart->id,
            'quantity' => $data['quantity'],
        ]);

        return $newCartItem->exists;
    }
}
