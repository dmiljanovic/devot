<?php

namespace App\Repositories;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseRepository implements CrudInterface
{
    public function getAllWithPagination(Request $request): LengthAwarePaginator
    {
        $query = Expense::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->get('category'));
        }
        if ($request->has('price_min')) {
            $query->where('amount', '>=', $request->get('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('amount', '<=', $request->get('price_max'));
        }
        if ($request->has('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('date_from')));
        }
        if ($request->has('date_to')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('date_to')));
        }

        return $query->paginate(2)->withQueryString();
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
