<?php

namespace Tests\Browser\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Tests\Browser\Components\DeleteEvents;

class DeleteDuskPage extends Page
{
    // protected array $forValsAndLabelsAssocArr = [
    //     'name' => 'Name',
    //     'email' => 'Email',
    //     'password' => 'Password',
    // ];

    // protected array $assocArrOfFieldAndVal = [
    //     'name' => 'New Name',
    //     'email' => 'newemail@gmail.com',
    //     'password' => 'password123',
    // ];

    protected array $selectorsAliases = [
        '@deleteHeading' => '.delete-heading',
        '@deleteForm' => '.delete-form',
        '@csrfTokenHiddenField' => 'input[name="_token"]',
        '@deleteLabels' => '.delete-labels',
        '@smallTextFields' => '.small-text-inputs',
        '@deleteSubmitBtn' => '.delete-submit-btn',
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
        return route('user-delete-form', ['user' => $this->user->id]);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {


        $browser->assertRouteIs('user-delete-form', ['user' => $this->user->id]);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        $dynamicFieldsAliases = [];
        // foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
        //     $dynamicFieldsAliases['@' . $forValue . 'Label'] = 'label[for="' . $forValue . '"]';
        // }
        // foreach ($this->assocArrOfFieldAndVal as $field => $value) {
        //     $dynamicFieldsAliases['@' . $field . 'Field'] = 'input[name="' . $field . '"]';
        // }

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

    // public function confirmPresenceOfAllRequiredLabels(Browser $browser): void
    // {
    //     foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
    //         $browser->assertAttribute('@' . $forValue . 'Label', 'for', $forValue);
    //     }
    // }

    // public function confirmPresenceOfAllRequiredLabelsText(Browser $browser): void
    // {
    //     foreach ($this->forValsAndLabelsAssocArr as $forValue => $fieldLabel) {
    //         $browser->assertSeeIn('@' . $forValue . 'Label', Str::ucfirst($fieldLabel));
    //     };
    // }

    // public function confirmPresenceOfAllVisibleInputFields(Browser $browser): void
    // {
    //     $browser->assertVisible('@smallTextFields');
    //     foreach ($this->assocArrOfFieldAndVal as $fieldNameVal => $fieldValue) {
    //         $browser->assertAttribute('@' . $fieldNameVal . 'Field', 'name', (string) $fieldNameVal);
    //     };
    // }

    public function confirmPresenceOfSubmitButton(Browser $browser, string $submitBtnSelector): void
    {
        $browser->assertVisible($submitBtnSelector)
            ->assertAttribute($submitBtnSelector, 'type', 'submit')
        ;
    }


    public function confirmVisibleInputFieldsCanReceiveSubmitAndRedirectAfterEnteredData(Browser $browser, string $submitBtnSelector, string $redirectOrReloadRoute): void
    {
        // foreach ($this->assocArrOfFieldAndVal as $fieldNameVal => $entredValue) {
        //     $browser->type('@' . $fieldNameVal . 'Field', (string) $entredValue);
        // };
        $browser->press($submitBtnSelector)
            ->waitForRoute($redirectOrReloadRoute, seconds: 1)
            ->assertRouteIs($redirectOrReloadRoute)
            ->within(new DeleteEvents, function ($browser) {
                $browser->displayDeleteSuccessMessage();
            })
            ->screenshot('registrationSuccessful')
        ;
    }
}
