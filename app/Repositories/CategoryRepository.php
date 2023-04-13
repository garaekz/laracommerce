<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private Category $model
    ){}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(array $data, Category $model): bool
    {
        return $model->update($data);
    }

    public function delete(Category $model): bool | null {
        return $model->delete();
    }

    public function find($id): Category
    {
        return $this->model->find($id);
    }

    public function findBy(array $wheres, array $with = []): Category | null
    {
        return $this->model->where($wheres)->first();
    }

    public function findMultipleBy(array $where): Collection
    {
        return $this->model->where($where)->get();
    }
}
