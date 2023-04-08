<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

interface CategoryRepositoryInterface extends BaseModelRepositoryInterface
{
    public function update(array $data, Category $model): bool;
    public function delete(Category $model): bool | null;
}
