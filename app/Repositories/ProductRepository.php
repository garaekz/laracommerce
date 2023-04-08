<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Product $model
    ){}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allPaginated(int $size = 12): LengthAwarePaginator
    {
        return $this->model->paginate($size);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(array $data, Product $model): bool
    {
        return $model->update($data);
    }

    public function delete(Product $model): bool | null {
        return $model->delete();
    }

    public function find($id): Product
    {
        return $this->model->find($id);
    }

    public function findBy($field, $value): Product | null
    {
        return $this->model->where($field, $value)->first();
    }

    public function findMultipleBy(array $where): Collection
    {
        return $this->model->where($where)->get();
    }
}
