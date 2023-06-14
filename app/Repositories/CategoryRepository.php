<?php

namespace App\Repositories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CrudInterface
{
    public function getAllWithPagination(Request $request): LengthAwarePaginator
    {
        $query = Category::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        return $query->paginate(2)->withQueryString();
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
