<?php

namespace App\Http\Requests;

use App\Enums\ContactService;
use App\Support\ContactFormAttempt;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => strtolower(trim($this->input('email')))]);
        }
        if (is_string($this->input('body'))) {
            $this->merge(['body' => str_replace(["\r\n", "\r"], "\n", $this->input('body'))]);
        }
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'min:2', 'max:120', 'not_regex:/[\x00-\x1f\x7f]/u'],
            'email' => ['bail', 'required', 'string', 'max:254', function (string $attribute, mixed $value, Closure $fail): void {
                if (preg_match('/[\x00-\x20\x7f]/', $value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $fail('Vul een geldig e-mailadres in.');
                }
            }],
            'service' => ['nullable', Rule::enum(ContactService::class)],
            'body' => ['bail', 'required', 'string', 'min:20', 'max:5000', 'not_regex:/[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]/u'],
            'website' => ['prohibited'],
            'attempt' => ['bail', 'required', 'string', 'size:113', function (string $attribute, mixed $value, Closure $fail): void {
                if (! app(ContactFormAttempt::class)->valid($this->session(), $value)) {
                    $fail('Het formulier is verlopen. Je tekst blijft staan; controleer je gegevens en probeer opnieuw.');
                }
            }],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => 'Vul je naam in.',
            'name.min' => 'Vul een naam van minstens 2 tekens in.',
            'name.max' => 'Gebruik maximaal 120 tekens voor je naam.',
            'name.not_regex' => 'Gebruik één regel voor je naam.',
            'email.required' => 'Vul je e-mailadres in.',
            'email.max' => 'Gebruik een e-mailadres van maximaal 254 tekens.',
            'service.enum' => 'Kies een dienst uit de lijst, of laat de keuze open.',
            'body.required' => 'Vertel kort wat je wilt maken.',
            'body.min' => 'Vertel iets meer over je idee: gebruik minstens 20 tekens.',
            'body.max' => 'Gebruik maximaal 5000 tekens voor je bericht.',
            'body.not_regex' => 'Je bericht bevat ongeldige tekens. Verwijder die en probeer opnieuw.',
            'website.prohibited' => 'Je bericht kon niet worden verwerkt. Probeer opnieuw of mail me rechtstreeks.',
            'attempt.required' => 'Open het formulier opnieuw en probeer nog eens.',
            'attempt.size' => 'Het formulier is verlopen. Controleer je gegevens en probeer opnieuw.',
            '*.string' => 'Vul hier tekst in.',
        ];
    }
}
