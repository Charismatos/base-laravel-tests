<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Override;
use Tests\TestCase;

class DeleteRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Confirm the deletepage access response has no errors
     * 
     * @test
     * @group navigation
     */

    protected object $user;
    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($this->user);
    }


    public function test_delete_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the delete page
        $response = $this->get(route('user-delete-form', ['user' => $this->user->id]));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_delete_form_deletes_and_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - 
        $response = $this->delete(route('user-delete-submit', ['user' => $this->user->id]));

        // Assert - 
        $this->assertModelMissing($this->user);
        $response->assertStatus(302);
        $response->assertRedirect(route('user-register-form'));
    }
}
