<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $user = User::find(1);
        $categories = Category::all();
        $billId = Bill::insertGetId([
            'user_id' => $user->id
        ]);

        foreach ($categories as $category) {
            Expense::insert([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'bill_id' => $billId,
                'description' => 'Test description...',
                'amount' => random_int(1, 10000),
                'created_at' => now()
            ]);
        }

        $expensesSum = Expense::where('bill_id', $billId)->sum('amount');

        $bill = Bill::find($billId);
        $bill->amount = $expensesSum;
        $bill->save();

        $user->total_amount -= $expensesSum;
        $user->save();
    }
}
