<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RegisterDuskPage;
use Tests\DuskTestCase;

class RegisterDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_presence_of_register_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterDuskPage);
        });
    }

    public function test_presence_of_register_form_and_its_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterDuskPage)
                ->confirmPresenceOfCorrectFormTittle('@registerHeading', 'REGISTER')
                ->confirmPresenceOfHTMLFormWithRightAttributes('@registerForm', route('user-register-submit'))
                ->confirmPresenceOfCsrfProtection('@csrfTokenHiddenField')
                ->confirmPresenceOfAllRequiredLabels()
                ->confirmPresenceOfAllRequiredLabelsText()
                ->confirmPresenceOfAllVisibleInputFields()
                ->confirmPresenceOfSubmitButton('@registerSubmitBtn')
            ;
        });
    }

    public function test_ability_of_register_form_to_receive_entered_data_and_submit_it(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterDuskPage)
                ->confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData('@registerSubmitBtn', 'user-login-form')
            ;
        });
    }
}
