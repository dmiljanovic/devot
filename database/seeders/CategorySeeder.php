<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Food'
            ],
            [
                'name' => 'Household items'
            ],
            [
                'name' => 'Garden'
            ],
            [
                'name' => 'Garage items'
            ],
            [
                'name' => 'Kitchen items'
            ],
            [
                'name' => 'Clothes'
            ],
            [
                'name' => 'Footwear'
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
