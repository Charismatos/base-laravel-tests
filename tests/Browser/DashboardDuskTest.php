<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\DashboardDuskPage;
use Tests\DuskTestCase;

class DashboardDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_presence_of_dashboard_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new DashboardDuskPage($user));
        });
    }

    public function test_dashboard_displays_correct_components(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $browser->visit(new DashboardDuskPage($user))
                ->confirmPresenceOfCorrectPageTittle('@dashboardHeading', 'DASHBOARD')
                ->confirmPresenceOfCorrectPageHeadings(['@dashboardWelcome' => 'Welcome,', '@dashboardHeadingUsername' => 'Mike Dean'])
                ->confirmPresenceOfCorrectPageSectionHeadings('@dashboardSectionHeadings', ['User Details'])
                ->confirmPresenceOfCorrectPageDataFromDatabase('@dashboardModelDetails', ['Name:' => 'Mike Dean', 'Email:' => 'mike@gmail.com',])
                ->confirmPresenceOfCorrectPageLinksWithParameters(['user-edit-form', 'user-delete-form'])
                ->confirmPresenceOfCorrectPageLinksWithoutPrameters('user-logout')
                ->confirmPresenceOfCorrectPageButtons(['Edit Profile', 'Logout', 'Delete Account'])
            ;
        });
    }

    public function test_dashboard_responds_correctly_under_given_conditions(): void
    {
        $this->browse(function (Browser $browser) {
            $user1 = User::factory()->create([
                'name' => 'Mike Dean',
                'email' => 'mike@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $user2 = User::factory()->create([
                'name' => 'John Daniels',
                'email' => 'john@gmail.com',
                'password' => Hash::make('password123')
            ]);
            $guestUser = null;
            $browser->visit(new DashboardDuskPage($user1))
                ->confirmCorrectUserIsRedirectedToEditPage($user1, '@dashboardEditButton', 'user-edit-form')
                ->confirmAfterLogoutUserBecomesGuestAndRedirectToLoginPage($user1, '@dashboardLogoutButton', 'user-login-form', 'LOGIN')
                ->confirmCorrectUserIsRedirectedToDeleteAccountPage($user1, '@dashboardDeleteButton', 'user-delete-form')
                ->confirmCorrectResponseAccordingToUser($user1, '@dashboardLogoutButton', $user2)
            ;
        });
    }
}
