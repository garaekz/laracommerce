<?php

namespace App\Repositories;

use App\Contracts\CartItemRepositoryInterface;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CartItemRepository implements CartItemRepositoryInterface
{
    public function __construct(
        private CartItem $model
    ){}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allPaginated(int $size = 12): LengthAwarePaginator
    {
        return $this->model->paginate($size);
    }

    public function create(array $data): CartItem
    {
        return $this->model->create($data);
    }

    public function update(array $data, CartItem $model): bool
    {
        return $model->update($data);
    }

    public function delete(CartItem $model): bool | null {
        return $model->delete();
    }

    public function find($id): CartItem
    {
        return $this->model->find($id);
    }

    public function findBy(array $wheres, array $with = []): CartItem | null
    {
        return $this->model->where($wheres)->first();
    }

    public function findMultipleBy(array $where): ?CartItem
    {
        return $this->model->where($where)->get();
    }
}
