<?php

namespace Database\Seeders;

use App\Models\Category;
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

        foreach ($categories as $category) {
            DB::table('expenses')->insert([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'description' => 'Test description...',
                'amount' => random_int(1, 10000),
                'created_at' => now()
            ]);
        }
    }
}
