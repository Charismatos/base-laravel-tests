<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\UpdateDuskPage;
use Tests\DuskTestCase;

class UpdateDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_presence_of_update_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new UpdateDuskPage($user));
        });
    }

    public function test_presence_of_update_form_and_its_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new UpdateDuskPage($user))
                ->confirmPresenceOfCorrectFormTittle('@updateHeading', 'EDIT PROFILE')
                ->confirmPresenceOfHTMLFormWithRightAttributes('@updateForm', route('user-edit-submit', ['user' => $user->id]))
                ->confirmPresenceOfCsrfProtection('@csrfTokenHiddenField')
                ->confirmPresenceOfAllRequiredLabels()
                ->confirmPresenceOfAllRequiredLabelsText()
                ->confirmPresenceOfAllVisibleInputFields()
                ->confirmPresenceOfSubmitButton('@updateSubmitBtn')
            ;
        });
    }

    public function test_ability_of_update_form_to_receive_entered_data_and_submit_it(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new UpdateDuskPage($user))
                ->confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData('@updateSubmitBtn', 'user-login-form')
            ;
        });
    }
}
