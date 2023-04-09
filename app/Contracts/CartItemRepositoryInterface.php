<?php

namespace App\Contracts;

use App\Models\CartItem;

interface CartItemRepositoryInterface extends BaseModelRepositoryInterface
{
    public function update(array $data, CartItem $model): bool;
    public function delete(CartItem $model): bool | null;
    public function findMultipleBy(array $where): ?CartItem;
}
