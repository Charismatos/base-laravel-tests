<?php

namespace Tests\Browser\Pages;

use Illuminate\Support\Uri;
use Laravel\Dusk\Browser;

class HomePage extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('home');
    }


    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertRouteIs('home')
            ->assertTitle('Base Laravel Tests');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@homeHeading' => '.home-heading',
        ];
    }

    public function confirmProjectWebUrl(Browser $browser): void
    {
        $projectWebUrl = route('home') . '/';
        $browser->assertUrlIs($projectWebUrl);
    }

    public function confirmProjectWebScheme(Browser $browser): void
    {
        $projectWebUri = Uri::of(route('home'));
        $projectWebScheme = $projectWebUri->scheme(); // 'http'
        $browser->assertSchemeIs($projectWebScheme);
    }

    public function confirmProjectWebSchemeIsNot(Browser $browser): void
    {
        $browser->assertSchemeIsNot('https');
    }

    public function confirmProjectWebHostIs(Browser $browser): void
    {
        $projectWebUri = Uri::of(route('home'));
        $projectWebHost = $projectWebUri->host(); // 'laravel.test'
        $browser->assertHostIs($projectWebHost);
    }

    public function confirmProjectWebHostIsNot(Browser $browser): void
    {
        $browser->assertHostIsNot('base_laravel_tests.com');
    }

    public function confirmProjectWebPortIs(Browser $browser): void
    {
        $projectWebUri = Uri::of(route('home'));
        $projectWebPort = $projectWebUri->port(); // null
        $browser->assertPortIs($projectWebPort);
    }

    public function confirmProjectWebPortIsNot(Browser $browser): void
    {
        $browser->assertPortIsNot('80');
    }
}
