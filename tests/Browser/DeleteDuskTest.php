<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\DeleteDuskPage;
use Tests\DuskTestCase;

class DeleteDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_presence_of_delete_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new DeleteDuskPage($user));
        });
    }

    public function test_presence_of_delete_form_and_its_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new DeleteDuskPage($user))
                ->confirmPresenceOfCorrectFormTittle('@deleteHeading', 'DELETE PROFILE')
                ->confirmPresenceOfHTMLFormWithRightAttributes('@deleteForm', route('user-delete-submit', ['user' => $user->id]))
                ->confirmPresenceOfCsrfProtection('@csrfTokenHiddenField')
                // ->confirmPresenceOfAllRequiredLabels()
                // ->confirmPresenceOfAllRequiredLabelsText()
                // ->confirmPresenceOfAllVisibleInputFields()
                ->confirmPresenceOfSubmitButton('@deleteSubmitBtn')
            ;
        });
    }

    public function test_ability_of_delete_form_to_delete_data(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new DeleteDuskPage($user))
                ->confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData('@deleteSubmitBtn', 'user-register-form')
            ;
        });
    }
}
