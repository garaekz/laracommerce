<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

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

    public function update(array $data, $model): bool
    {
        return $model->update($data);
    }

    public function delete($model): bool | null {
        return $model->delete();
    }

    public function find($id): Category
    {
        return $this->model->find($id);
    }

    public function findBy($field, $value): Category
    {
        return $this->model->where($field, $value)->first();
    }
}
