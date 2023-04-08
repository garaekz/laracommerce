<?php

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ){}

    public function fetchAll(): Collection {
        return $this->repository->all();
    }

    public function create(array $data): Category {
        $data['slug'] = Str::slug($data['name']);
        return $this->repository->create($data);
    }

    public function update(Category $category, array $data): bool {
        return $this->repository->update($data, $category);
    }

    public function delete(Category $category): bool {
        return $this->repository->delete($category);
    }
}
