<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Confirm the editpage access response has no errors
     * 
     * @test
     * @group navigation
     */
    public function test_edit_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);


        // Act - Request the edit page
        $response = $this->actingAs($user)->get(route('user-edit-form', $user->toArray()));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_edit_form_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $user = User::factory()->create([
            'name' => 'Mike Dean',
            'email' => 'mike@gmail.com',
            'password' => 'password123',
        ]);


        $newUserData = [
            'name' => 'New Name',
            'email' => 'newemail@gmail.com',
            'password' => 'newpassword123',
        ];

        // Act - Request the home page
        $response = $this->actingAs($user)->patch(route('user-edit-submit', $newUserData), [
            $user->name = $newUserData['name'],
            $user->email = $newUserData['email'],
            $user->password = $newUserData['password'],
        ]);

        // Assert - Check for a 200 OK status
        $response->assertStatus(302);
        $response->assertRedirect(route('user-login-form'));
    }
}
