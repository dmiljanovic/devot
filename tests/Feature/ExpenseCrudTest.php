<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExpenseCrudTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private string $token;
    private Expense $expense;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
        $bill = Bill::factory()
            ->for($user)
            ->create();
        $category = Category::factory()->create();

        $this->expense = Expense::factory()
            ->for($user)
            ->for($category)
            ->for($bill)
            ->create();
    }

    /** @test */
    public function an_user_can_read_all_the_expenses(): void
    {
        $response = $this->get('/api/expenses');

        $response->assertSee($this->expense->description);
        $this->assertEquals(1,Expense::all()->count());
    }

    /** @test */
    public function an_user_can_read_single_expense(): void
    {
        $response = $this->get('/api/expenses/' . $this->expense->id);

        $response->assertSee($this->expense->description)->assertSee($this->expense->id);
    }

    /** @test */
    public function an_users_can_create_a_new_expense(): void
    {
        $expense = Expense::factory()
            ->for(User::factory()->create())
            ->for(Category::factory()->create())
            ->make();

        $response = $this->post('/api/expenses', $expense->toArray());

        $response->assertSee('Expense successfully stored.');
    }

    /** @test */
    public function an_users_can_update_its_expense(): void
    {
        $response = $this->put(
            '/api/expenses/' . $this->expense->id,
            ['description' => 'updated']
        );

        $response->assertSee('Expense successfully updated.');
    }

    /** @test */
    public function an_users_can_delete_its_expense(): void
    {
        $response = $this->delete('/api/expenses/' . $this->expense->id);

        $response->assertSee('Expense successfully deleted.');
        $this->assertEquals(0,Expense::all()->count());
    }
}
