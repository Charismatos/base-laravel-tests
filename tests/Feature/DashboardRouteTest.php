<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Override;
use Tests\TestCase;

class DashboardRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Confirm the dashboard page access response has no errors
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

    public function test_dashboard_page_is_accessible_by_Authenticated_user(): void
    {
        $this->assertAuthenticatedAs($this->user);

        $response = $this->get(route('user-dashboard', ['user' => $this->user->id]));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    public function test_authenticated_user_model_data_exists_in_database(): void
    {


        $userData = $this->user->toArray();

        // Assert - 
        $this->assertAuthenticatedAs($this->user);
        $this->assertModelExists($this->user);
        $this->assertDatabaseHas('users', $userData);
    }

    public function test_authenticated_user_can_actually_fetch_model_data__that_exists_in_database(): void
    {


        $user = User::findOrFail($this->user->id);
        $expectedUserData = $user->toArray();
        $acturalUserData = $this->user->toArray();
        $keysToConsider = ['name', 'email'];

        // Assert - 
        $this->assertAuthenticatedAs($this->user);
        $this->assertArrayIsEqualToArrayOnlyConsideringListOfKeys($expectedUserData, $acturalUserData, $keysToConsider);
    }
}
