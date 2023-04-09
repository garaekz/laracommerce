<?php

namespace App\Repositories;

use App\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        private Cart $model
    ){}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allPaginated(int $size = 12): LengthAwarePaginator
    {
        return $this->model->paginate($size);
    }

    public function create(array $data): Cart
    {
        return $this->model->create($data);
    }

    public function update(array $data, Cart $model): bool
    {
        return $model->update($data);
    }

    public function delete(Cart $model): ?bool {
        return $model->delete();
    }

    public function find($id): Cart
    {
        return $this->model->find($id);
    }

    public function findBy(array $wheres): ?Cart
    {
        return $this->model->where($wheres)->first();
    }

    public function findMultipleBy(array $where): Collection
    {
        return $this->model->where($where)->get();
    }
}
