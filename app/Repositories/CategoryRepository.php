<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Category::paginate(2);
    }

    public function store(array $data): void
    {
        Category::create($data);
    }

    public function getById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function delete(int $id): void
    {
        Category::delete($id);
    }

    public function update(array $data): void
    {
        $category = Category::find($data['category']);
        $category->name = $data['name'];
        $category->save();
    }
}
