<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryCrudTest extends TestCase
{
    use DatabaseMigrations;

    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->token = JWTAuth::fromUser($user);
    }

    /** @test */
    public function a_user_can_read_all_the_categories(): void
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories?token=' . $this->token);

        $response->assertSee($category->name);
    }

    /** @test */
    public function a_user_can_read_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories/' . $category->id . '?token=' . $this->token);

        $response->assertSee($category->name)->assertSee($category->id);
    }

    /** @test */
    public function a_users_can_create_a_new_task(): void
    {
        $category = Category::factory()->make();

        $this->post('/api/categories?token=' . $this->token, $category->toArray());

        $this->assertEquals(1,Category::all()->count());
    }

    /** @test */
    public function a_users_can_update_a_category(): void
    {
        $category = Category::factory()->create();

        $this->put('/api/categories/' . $category->id . '?token=' . $this->token, ['name' => 'updated']);

        $response = $this->get('/api/categories?token=' . $this->token);

        $response->assertSee('updated');
    }

    /** @test */
    public function a_users_can_delete_a_task(): void
    {
        $category = Category::factory()->create();

        $this->delete('/api/categories/' . $category->id . '?token=' . $this->token);

        $this->assertEquals(0,Category::all()->count());
    }
}
