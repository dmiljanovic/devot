<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExpenseCrudTest extends TestCase
{
    use DatabaseMigrations;

    private string $token;
    private Expense $expense;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->token = JWTAuth::fromUser($user);
        $this->expense = Expense::factory()
            ->for(User::factory()->create())
            ->for(Category::factory()->create())
            ->create();
    }

    /** @test */
    public function a_user_can_read_all_the_expenses(): void
    {
        $response = $this->get('/api/expenses?token=' . $this->token);

        $response->assertSee($this->expense->description);
        $this->assertEquals(1,Category::all()->count());
    }

    /** @test */
    public function a_user_can_read_single_expense(): void
    {
        $response = $this->get('/api/expenses/' . $this->expense->id . '?token=' . $this->token);

        $response->assertSee($this->expense->description)->assertSee($this->expense->id);
    }

    /** @test */
    public function a_users_can_create_a_new_expense(): void
    {
        $expense = Expense::factory()
            ->for(User::factory()->create())
            ->for(Category::factory()->create())
            ->make();

        $response = $this->post('/api/expenses?token=' . $this->token, $expense->toArray());

        $response->assertSee('Expense successfully stored.');
    }

    /** @test */
    public function a_users_can_update_a_expense(): void
    {
        $response = $this->put(
            '/api/expenses/' . $this->expense->id . '?token=' . $this->token,
            ['description' => 'updated']
        );

        $response->assertSee('Expense successfully updated.');
    }

    /** @test */
    public function a_users_can_delete_a_expense(): void
    {
        $response = $this->delete('/api/expenses/' . $this->expense->id . '?token=' . $this->token);

        $response->assertSee('Expense successfully deleted.');
    }
}
