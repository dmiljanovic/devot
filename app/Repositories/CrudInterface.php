<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CrudInterface
{
    public function getAllWithPagination(Request $request): LengthAwarePaginator;

    public function store(array $data): Model;

    public function getById(int $id): ?Model;

    public function delete(int $id): Model;

    public function update(array $data): void;
}
