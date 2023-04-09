<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
    ){}

    public function fetchAll(): Collection {
        return $this->repository->all();
    }

    public function fetchPaginated(): LengthAwarePaginator {
        return $this->repository->allPaginated();
    }

    public function create(array $data): Product {
        $data['slug'] = Str::slug($data['name']);
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'local');
        }
        $slugExists = $this->repository->findBy(['slug', $data['slug']]);
        if ($slugExists) {
            $data['slug'] = "{$data['slug']}-{$this->generateUlid()}";
        }

        return $this->repository->create($data);
    }

    public function update(Product $product, array $data): bool {
        if (isset($data['image'])) {
            if ($product->image) {
                Storage::disk('local')->delete($product->image);
            }
            $data['image'] = $data['image']->store('products', 'local');
        }
        return $this->repository->update($data, $product);
    }

    public function delete(Product $product): bool {
        return $this->repository->delete($product);
    }

    public function generateUlid(): string {
        return strtolower((string) Str::ulid());
    }
}
