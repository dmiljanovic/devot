<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BillRepository
{

    public function getAllWithPagination(Request $request): LengthAwarePaginator
    {
        $query = Bill::query()->where('user_id', $request->user()->id);

        if ($request->has('category')) {
            $query->with(['expenses' => function ($q) use ($request) {
                $q->where('category_id', $request->get('category'));
            }]);
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

    public function store(): Bill
    {
        $bill = new Bill();
        $bill->user_id = request()?->user()->id;
        $bill->save();

        return $bill;
    }

    public function getById(int $id, $withExpenses = false): Model
    {
        $query = Bill::query()->where('id', $id);

        if ($withExpenses) {
            $query->with('expenses');
        }

        return $query->first();
    }

    public function delete(int $id): Bill
    {
        $bill = Bill::find($id);
        $bill->delete();

        return $bill;
    }
    public function decreaseAmount(Expense $expense): void
    {
        $bill = $this->getById($expense->bill_id);
        $bill->amount -= $expense->amount;
        $bill->save();
    }
    public function increaseAmount(Expense $expense): void
    {
        $bill = $this->getById($expense->bill_id);
        $bill->amount += $expense->amount;
        $bill->save();
    }
}
