<?php

namespace App\Contracts;

use App\Models\Cart;

interface CartRepositoryInterface extends BaseModelRepositoryInterface
{
    public function update(array $data, Cart $model): bool;
    public function delete(Cart $model): bool | null;
}
