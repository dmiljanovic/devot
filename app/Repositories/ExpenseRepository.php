<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseRepository implements CrudInterface
{
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Expense::paginate(2);
    }

    public function store(array $data): Expense
    {
        return Expense::create($data);
    }

    public function getById(int $id): ?Expense
    {
        return Expense::find($id);
    }

    public function delete(int $id): Expense
    {
        $expense = Expense::find($id);
        $expense->delete();

        return $expense;
    }

    public function update(array $data): void
    {
        $expense = Expense::find($data['expense']);
        $expense->category_id = $data['category_id'];
        $expense->description = $data['description'];
        $expense->save();
    }
}
