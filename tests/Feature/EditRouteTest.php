<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Override;
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


    public function test_edit_form_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the edit page
        $response = $this->get(route('user-edit-form', ['user' => $this->user->id]));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_edit_form_updates_database_and_redirects_after_submission(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $newUserData = [
            'name' => 'New Name',
            'email' => 'newemail@gmail.com',
            'password' => Hash::make('newpassword123'),
        ];

        // Act - Request the home page
        $response = $this->patch(route('user-edit-submit', ['user' => $this->user->id]), $newUserData);

        // Assert - 
        $this->assertModelExists($this->user);
        $this->assertDatabaseHas('users', $newUserData);
        $response->assertStatus(302);
        $response->assertRedirect(route('user-login-form'));
    }


    public function test_edit_form_does_not_submit_invalid_data(): void
    {
        // Assign (Models, Factories, Mockables, etc.)
        $newUserData = [
            'name' => '',
            'email' => 'newemail@gmail.com',
            'password' => 'newpassword123',
        ];

        // Act - Request the home page
        $response = $this->patch(route('user-edit-submit', ['user' => $this->user->id]), $newUserData);

        // Assert - 
        $response->assertInvalid();
        $this->assertModelExists($this->user);
        $this->assertDatabaseMissing('users', $newUserData);
        $response->assertStatus(302);
        $response->assertRedirectBack();
    }
}
