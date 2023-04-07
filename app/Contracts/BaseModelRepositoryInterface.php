<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseModelRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Model;

    public function update(array $data, $model): bool;

    public function delete($model): bool | null;

    public function find($id): Model;

    public function findBy($field, $value): Model;
}
