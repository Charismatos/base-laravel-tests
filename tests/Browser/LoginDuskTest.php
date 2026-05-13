<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginDuskPage;
use Tests\DuskTestCase;

class LoginDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_presence_of_login_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginDuskPage);
        });
    }

    public function test_presence_of_login_form_and_its_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginDuskPage)
                ->confirmPresenceOfCorrectFormTittle('@loginHeading', 'LOGIN')
                ->confirmPresenceOfHTMLFormWithRightAttributes('@loginForm', route('user-login-submit'))
                ->confirmPresenceOfCsrfProtection('@csrfTokenHiddenField')
                ->confirmPresenceOfAllRequiredLabels()
                ->confirmPresenceOfAllRequiredLabelsText()
                ->confirmPresenceOfAllVisibleInputFields()
                ->confirmPresenceOfSubmitButton('@loginSubmitBtn')
            ;
        });
    }

    public function test_ability_of_login_form_to_receive_entered_data_and_submit_it(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginDuskPage)
                ->confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData('@loginSubmitBtn', 'user-dashboard')
            ;
        });
    }
}
