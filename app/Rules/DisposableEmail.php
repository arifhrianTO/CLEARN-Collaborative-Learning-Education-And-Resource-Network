<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Beeyev\DisposableEmailFilter\DisposableEmailFilter;

class DisposableEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $filter = new DisposableEmailFilter();
        if ($filter->isDisposableEmailAddress($value)) {
            $fail('Harap gunakan email yang valid. Email sementara (disposable email) tidak diizinkan.');
        }
    }
}
