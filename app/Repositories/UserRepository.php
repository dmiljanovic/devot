<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;

class UserRepository
{
    public function updateAmount(Expense $expense): void
    {
        $user = $this->getById($expense->user_id);
        $user->total_amount -= $expense->amount;
        $user->save();
    }

    public function getById(int $id)
    {
        return User::find($id);
    }
}