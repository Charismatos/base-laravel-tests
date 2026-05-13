<?php

namespace Tests\Browser\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Tests\Browser\Components\DashboardEvents;

class DashboardDuskPage extends Page
{

    protected array $selectorsAliases = [
        '@dashboardHeading' => '.dashboard-heading',
        '@dashboardActionsBtns' => '.dashboard-actions-btns',
        '@dashboardWelcome' => '.welcome-user',
        '@dashboardHeadingUsername' => '.heading-user-name',
        '@dashboardSectionHeadings' => '.section-headings',
        '@dashboardModelDetails' => '.model-details',
        '@dashboardModelAttributes' => '.model-attributes',
        '@dashboardModelAttributesValues' => '.model-attribute-values',
        '@dashboardActionLinks' => '.action-links',
        '@dashboardActionLinkWithParams' => '.action-link-with-params',
        '@dashboardActionLink' => '.action-link',
        '@dashboardEditButton' => '.edit-button',
        '@dashboardDeleteButton' => '.delete-button',
        '@dashboardLogoutButton' => '.logout-button',
    ];

    protected object $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('user-dashboard', ['user' => $this->user->id]);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {


        $browser->assertRouteIs('user-dashboard', ['user' => $this->user->id]);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        $dynamicFieldsAliases = [];


        return array_merge($this->selectorsAliases, $dynamicFieldsAliases);
    }

    public function confirmPresenceOfCorrectPageTittle(Browser $browser, string $selector, string $expectedText): void
    {

        $browser->assertSeeIn($selector, $expectedText);
    }

    public function confirmPresenceOfCorrectPageHeadings(Browser $browser, array $assocArrOfSelectorsAndText): void
    {
        foreach ($assocArrOfSelectorsAndText as $selector => $text) {
            $browser->assertSeeIn($selector, $text);
        }
    }

    public function confirmPresenceOfCorrectPageSectionHeadings(Browser $browser, string $sectionHeadingsClass, array $sectionHeadingsValues): void
    {

        foreach ($sectionHeadingsValues as $sectionHeadingValue) {
            $browser->assertSeeIn($sectionHeadingsClass, $sectionHeadingValue);
        }
    }

    public function confirmPresenceOfCorrectPageDataFromDatabase(Browser $browser, string $modelDetails, array $assocArrOfAttributesAndDBValues): void
    {

        foreach ($assocArrOfAttributesAndDBValues as $attribute => $dbValue) {
            $browser->within($modelDetails, function ($eachChildElement) use ($attribute, $dbValue) {
                $eachChildElement->assertSee($attribute)
                    ->assertSee($dbValue);
            });
        }
    }

    public function confirmPresenceOfCorrectPageLinksWithParameters(Browser $browser, array $linkRoutes): void
    {
        $position = 1;
        foreach ($linkRoutes as $linkRoute) {
            $eachSelector = "@dashboardActionLinkWithParams:nth-of-type({$position})";
            $browser->assertAttribute($eachSelector, 'href', route($linkRoute, ['user' => $this->user->id]));
            $position++;
        }
    }

    public function confirmPresenceOfCorrectPageLinksWithoutPrameters(Browser $browser, string $linkRoute): void
    {

        $browser->assertAttribute('@dashboardActionLink', 'href', route($linkRoute));
    }

    public function confirmPresenceOfCorrectPageButtons(Browser $browser, array $buttonsText): void
    {
        foreach ($buttonsText as $buttonText) {
            $browser->assertSee($buttonText);
        }
    }

    public function confirmCorrectUserIsRedirectedToEditPage(Browser $browser, User $user, string $linkButtonTextSelector, string $redirectRoute): void
    {

        $browser->loginAs($user)
            ->visitRoute('user-dashboard', ['user' => $user->id])
            ->waitFor($linkButtonTextSelector)
            ->click($linkButtonTextSelector)
            ->assertRouteIs($redirectRoute, ['user' => $user->id])
        ;
    }

    public function confirmAfterLogoutUserBecomesGuestAndRedirectToLoginPage(Browser $browser, User $user, string $logOutLink, string $redirectRoute, string $whatUserSeesAfterLogout): void
    {

        $browser->loginAs($user)
            ->visitRoute('user-dashboard', ['user' => $user->id])
            ->waitFor($logOutLink)
            ->click($logOutLink)
            ->waitForRoute($redirectRoute)
            ->assertGuest()
            ->assertRouteIs($redirectRoute)
            ->assertSee($whatUserSeesAfterLogout)
        ;
    }


    public function confirmCorrectUserIsRedirectedToDeleteAccountPage(Browser $browser,  User $user, string $deleteLink, string $redirectRoute): void
    {

        $browser->loginAs($user)
            ->visitRoute('user-dashboard', ['user' => $user->id])
            ->waitFor('@dashboardDeleteButton')
            ->click($deleteLink)
            ->waitForRoute($redirectRoute, ['user' => $user->id], 1)
            ->assertRouteIs($redirectRoute, ['user' => $user->id])
        ;
    }

    public function confirmCorrectResponseAccordingToUser(Browser $browser, User $user1, string $logOutLink,  User $user2): void
    {
        $browser->loginAs($user1)
            ->visitRoute('user-dashboard', ['user' => $user1->id])
            ->assertSee('Welcome, ' . $user1->name)
            ->assertSee($user1->email)
            ->assertDontSee($user2->name)
        ;

        $browser->click($logOutLink)
            ->waitForRoute('user-login-form')
            ->assertRouteIs('user-login-form')
            ->loginAs($user2)
            ->visitRoute('user-dashboard', ['user' => $user2->id])
            ->assertSee('Welcome, ' . $user2->name)
            ->assertSee($user2->email)
            ->assertDontSee($user1->name)
        ;
    }
}
