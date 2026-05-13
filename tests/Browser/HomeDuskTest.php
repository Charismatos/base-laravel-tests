<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class HomeDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_home_page_is_correctly_displayed(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                ->waitFor('@homeHeading')
                ->assertSeeIn('@homeHeading', 'Base Laravel Tests')
            ;
        });
    }

    public function test_project_contains_the_right_uri_components(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                ->confirmProjectWebUrl()
                ->confirmProjectWebScheme()
                ->confirmProjectWebSchemeIsNot()
                ->confirmProjectWebHostIs()
                ->confirmProjectWebHostIsNot()
                ->confirmProjectWebPortIs()
                ->confirmProjectWebPortIsNot()
            ;
        });
    }
}
