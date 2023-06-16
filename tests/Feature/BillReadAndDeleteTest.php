<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BillReadAndDeleteTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private Bill $bill;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->bill = Bill::factory()
            ->for($user)
            ->create();
    }

    /** @test */
    public function an_user_can_read_all_own_bills(): void
    {
        $response = $this->get('/api/bills');

        $response->assertSee($this->bill->amount);
        $this->assertEquals(1,Bill::all()->count());
    }

    /** @test */
    public function an_user_can_read_single_own_bill(): void
    {
        $response = $this->get('/api/bills/' . $this->bill->id);

        $response->assertSee($this->bill->user_id)->assertSee($this->bill->id);
    }

    /** @test */
    public function an_user_can_delete_own_bill(): void
    {
        $response = $this->delete('/api/bills/' . $this->bill->id);

        $response->assertSee('Bill successfully deleted.');
        $this->assertEquals(0,Bill::all()->count());
    }
}
