<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\User;

class UserRepository
{
    public function decreaseTotalAmount(Expense $expense): void
    {
        $user = $this->getById($expense->user_id);
        $user->total_amount -= $expense->amount;
        $user->save();
    }
    public function increaseTotalAmount(Expense|Bill $model): void
    {
        $user = $this->getById($model->user_id);
        $user->total_amount += $model->amount;
        $user->save();
    }

    public function getById(int $id)
    {
        return User::find($id);
    }
}
