<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryCrudTest extends TestCase
{
    use DatabaseMigrations;

    private Category $category;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function a_user_can_read_all_the_categories(): void
    {
        $response = $this->get('/api/categories');

        $response->assertSee($this->category->name);
        $this->assertEquals(1,Category::all()->count());
    }

    /** @test */
    public function a_user_can_read_single_category(): void
    {
        $response = $this->get('/api/categories/' . $this->category->id);

        $response->assertSee($this->category->name)->assertSee($this->category->id);
    }

    /** @test */
    public function a_users_can_create_a_new_category(): void
    {
        $category = Category::factory()->make();

        $response = $this->post('/api/categories', $category->toArray());

        $response->assertSee('Category successfully stored.');
    }

    /** @test */
    public function a_users_can_update_a_category(): void
    {
        $response =$this->put('/api/categories/' . $this->category->id, ['name' => 'updated']);

        $response->assertSee('Category successfully updated.');
    }

    /** @test */
    public function a_users_can_delete_a_category(): void
    {
        $response = $this->delete('/api/categories/' . $this->category->id);

        $response->assertSee('Category successfully deleted.');
        $this->assertEquals(0,Category::all()->count());
    }
}
