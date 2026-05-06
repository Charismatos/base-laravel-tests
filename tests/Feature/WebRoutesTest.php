<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    // use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_normal_route(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertCookieMissing('test-cookie');
        $response->assertHeaderMissing('X-Test-Header');
        $response->assertSessionMissing('session-test-key');
        $response->assertSee('Base Laravel Tests');
        $response->assertSeeInOrder(['Base', 'Laravel', 'Tests']);
        $response->assertSeeText('Base Laravel Tests');
        $response->assertDontSee('How To Use This Image');
        $response->assertViewIs('home');
        $response->assertViewMissing('test-view-data-key');
    }
}
