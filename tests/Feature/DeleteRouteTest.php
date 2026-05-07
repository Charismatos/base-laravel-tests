<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function test_delete_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);


        // Act - Request the delete page
        $response = $this->actingAs($user)->get(route('user-delete-form', $user->toArray()));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_delete_form_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);

        // Act - Request the home page
        $response = $this->actingAs($user)->delete(route('user-delete-submit'));

        // Assert - Check for a 200 OK status
        $response->assertStatus(302);
        $response->assertRedirect(route('user-register-form'));
    }
}
