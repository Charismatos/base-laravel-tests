<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
        $response = $this->get(route('user-login-form'));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }


    public function test_login_form_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => Hash::make('password123'),
        ]);


        // Act - Request the home page
        $response = $this->post(route('user-login-submit'), [
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);

        // Assert - Check for a 200 OK status
        $this->assertAuthenticatedAs($user);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('user-dashboard', ['user' => $user->id]);
    }
}
