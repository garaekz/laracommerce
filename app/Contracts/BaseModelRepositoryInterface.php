<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseModelRepositoryInterface
{
    public function all();

    public function create(array $data);

    // Both methods need to have different signatures
    // public function update(array $data, $model): bool;
    // public function delete(Model $model): bool | null;

    public function find($id);

    public function findBy(array $wheres);
    public function findMultipleBy(array $where);
}
