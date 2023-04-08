<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseModelRepositoryInterface
{
    public function allPaginated(int $size = 12): LengthAwarePaginator;
    public function update(array $data, Product $model): bool;
    public function delete(Product $model): bool | null;
}
