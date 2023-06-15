<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExpenseAggregateTest extends TestCase
{
    use DatabaseMigrations;

    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
        Expense::factory()
            ->count(5)
            ->for(User::factory()->create())
            ->for(Category::factory()->create())
            ->create();
    }
    /** @test */
    public function an_user_can_see_aggregate_expenses(): void
    {
        $response = $this->get('/api/expenses/aggregate/this_year');

        $response->assertSee(Expense::all()->sum('amount'));
    }
}
