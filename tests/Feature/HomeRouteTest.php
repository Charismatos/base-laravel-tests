<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeRouteTest extends TestCase
{
    // use RefreshDatabase, WithFaker;
    /**
     * Confirm the homepage access response has no errors
     * 
     * @test
     * @group navigation
     */
    public function test_home_page_is_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->get(route('home'));

        // Assert - Check for a 200 OK status
        $response->assertStatus(200);
    }

    /**
     * Confirm the homepage access failure displays has "not found" error
     * 
     * @test
     * @group navigation
     */
    public function test_home_page_is_not_accessible(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->get('/hme');

        // Assert - Check for a 404 Not Found status
        $response->assertStatus(404);
    }


    /**
     * Confirm the homepage access using wrong request method returns "method not allowed" error
     * 
     * @test
     * @group navigation
     */
    public function test_home_page_access_wrong_http_method_returns_405(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->post('/');

        // Assert - Check for a 405 Method Not Allowed status
        $response->assertStatus(405);
    }

    /**
     * Confirm the homepage does not contain sensitive data when accessed
     * 
     * @test
     * @group navigation
     */
    public function test_home_page_does_not_contain_sensitive_data(): void
    {
        // Assign (Models, Factories, Mockables, etc.)


        // Act - Request the home page
        $response = $this->get(route('home'));

        // Assert - Check for the absence of sensitive data in the response
        $response->assertDontSee('password');
        $response->assertDontSee('api_key');
    }
}
