<?php

namespace Tests\Browser\Pages;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\RegistrationEvents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\Browser\Components\LoginEvents;

class LoginDuskPage extends Page
{
    protected array $forValsAndLabelsAssocArr = [
        'email' => 'Email',
        'password' => 'Password',
    ];

    protected array $assocArrOfFieldAndVal = [
        'email' => 'mike@gmail.com',
        'password' => 'password123',
    ];

    protected array $selectorsAliases = [
        '@loginHeading' => '.login-heading',
        '@loginForm' => '.login-form',
        '@csrfTokenHiddenField' => 'input[name="_token"]',
        '@loginLabels' => '.login-labels',
        '@smallTextFields' => '.small-text-inputs',
        '@loginSubmitBtn' => '.login-submit-btn',
    ];

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('user-login-form');
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertRouteIs('user-login-form');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        $dynamicFieldsAliases = [];
        foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
            $dynamicFieldsAliases['@' . $forValue . 'Label'] = 'label[for="' . $forValue . '"]';
        }
        foreach ($this->assocArrOfFieldAndVal as $field => $value) {
            $dynamicFieldsAliases['@' . $field . 'Field'] = 'input[name="' . $field . '"]';
        }

        return array_merge($this->selectorsAliases, $dynamicFieldsAliases);
    }

    public function confirmPresenceOfCorrectFormTittle(Browser $browser, string $selector, string $expectedText): void
    {
        $browser->assertSeeIn($selector, $expectedText);
    }

    public function confirmPresenceOfHTMLFormWithRightAttributes(Browser $browser, string $formClassValue, string $routeThatSubmitsForm): void
    {
        $browser->assertPresent($formClassValue)
            ->assertVisible($formClassValue)
            ->assertAttribute($formClassValue, 'action', $routeThatSubmitsForm)
        ;
    }

    public function confirmPresenceOfCsrfProtection(Browser $browser, string $tokenField): void
    {
        $browser->assertPresent($tokenField)
            ->assertValueIsNot($tokenField, '')
        ;
    }

    public function confirmPresenceOfAllRequiredLabels(Browser $browser): void
    {
        foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
            $browser->assertAttribute('@' . $forValue . 'Label', 'for', $forValue);
        }
    }

    public function confirmPresenceOfAllRequiredLabelsText(Browser $browser): void
    {
        foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
            $browser->assertSeeIn('@' . $forValue . 'Label', Str::ucfirst($fieldLabel));
        };
    }

    public function confirmPresenceOfAllVisibleInputFields(Browser $browser): void
    {
        $browser->assertVisible('@smallTextFields');
        foreach ($this->assocArrOfFieldAndVal as $fieldNameVal => $fieldValue) {
            $browser->assertAttribute('@' . $fieldNameVal . 'Field', 'name', (string) $fieldNameVal);
        };
    }

    public function confirmPresenceOfSubmitButton(Browser $browser, string $submitBtnSelector): void
    {
        $browser->assertVisible($submitBtnSelector)
            ->assertAttribute($submitBtnSelector, 'type', 'submit')
        ;
    }


    public function confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData(Browser $browser, string $submitBtnSelector, string $redirectOrReloadRoute): void
    {
        $user = User::factory()->create([
            'email' => $this->assocArrOfFieldAndVal['email'],
            'password' => Hash::make($this->assocArrOfFieldAndVal['password']),
        ]);
        foreach ($this->assocArrOfFieldAndVal as $fieldNameVal => $entredValue) {
            $browser->type('@' . $fieldNameVal . 'Field', (string) $entredValue);
        };
        $browser->press($submitBtnSelector)
            ->waitForRoute($redirectOrReloadRoute, ['user' => $user->id], 1)
            ->assertAuthenticated()
            ->assertAuthenticatedAs($user)
            ->assertRouteIs($redirectOrReloadRoute, ['user' => $user->id])
            ->waitFor('.login-successful', 1)
            ->within(new LoginEvents, function ($browser) {
                $browser->displayLoginSuccessMessage();
            })
            ->screenshot('logInSuccessful')
        ;
    }
}
