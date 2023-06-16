<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExpenseAggregateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
        $bill = Bill::factory()
            ->for($user)
            ->create();
        $category = Category::factory()->create();

        Expense::factory()
            ->count(5)
            ->for($user)
            ->for($bill)
            ->for($category)
            ->create();
    }
    /** @test */
    public function an_user_can_see_aggregate_expenses(): void
    {
        $response = $this->get('/api/expenses/aggregate/this_year');

        $response->assertSee(Expense::all()->sum('amount'));
    }
}
