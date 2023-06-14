<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CrudInterface
{
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Category::paginate(2);
    }

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function getById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function delete(int $id): Category
    {
        $category = Category::find($id);
        $category->delete();

        return $category;
    }

    public function update(array $data): void
    {
        $category = Category::find($data['category']);
        $category->name = $data['name'];
        $category->save();
    }
}
