<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Confirm the loginpage access response has no errors
     * 
     * @test
     * @group navigation
     */
    public function test_login_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->get('/login');

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }


    public function test_login_form_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);


        // Act - Request the home page
        $response = $this->post('/login', [
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);

        // Assert - Check for a 200 OK status
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }
}
