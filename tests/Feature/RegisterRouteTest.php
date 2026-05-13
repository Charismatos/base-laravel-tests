<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Confirm the registerpage access response has no errors
     * 
     * @test
     * @group navigation
     */
    public function test_register_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->get(route('user-register-form'));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_register_form_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $userData = [
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ];

        // Act - Request the home page
        $response = $this->post(route('user-register-submit'), $userData);

        // Assert - Check for a 200 OK status
        $response->assertStatus(302);
        $response->assertRedirect(route('user-login-form'));
    }

    public function test_user_model_exists__in_database(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create();


        // Assert - 
        $this->assertModelExists($user);
    }

    public function test_user_model_missing_in_database(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create();

        // Act - 
        $user->delete();

        // Assert - 
        $this->assertModelMissing($user);
    }

    public function test_register_form_data_was_sent_to_database(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $userData = [
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => bcrypt('password123'),
        ];

        // Act - 
        $response = $this->post(route('user-register-submit'), $userData);



        // Assert - 
        $response->assertValid();
        $this->assertDatabaseHas('users', $userData);
    }

    public function test_register_form_data_was_not_sent_to_database(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $userData = [
            'name' => '',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ];

        // Act -
        $response = $this->post(route('user-register-submit'), $userData);

        // Assert - 
        $response->assertInvalid();
        $this->assertDatabaseMissing('users', $userData);
    }

    public function test_database_has_10_users(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        User::factory(10)->create();


        // Assert - 
        $this->assertDatabaseCount('users', 10);
    }
}
